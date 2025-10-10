<?php
/**
 * Processing coordinator for PDFMaster.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class Processor
{
    public function __construct(
        private readonly FileHandler $file_handler,
        private readonly StirlingApi $stirling_api,
        private readonly Cleanup $cleanup
    ) {
    }

    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_pdfm_process_pdf', [$this, 'handle_ajax']);
        add_action('wp_ajax_nopriv_pdfm_process_pdf', [$this, 'handle_ajax']);
        add_action('wp_ajax_pdfm_download', [$this, 'download_file']);
        add_action('wp_ajax_nopriv_pdfm_download', [$this, 'download_file']);
        add_shortcode('pdfmaster_processor', [$this, 'render_shortcode']);
        add_action('pdfm_processor_cleanup_cron', [$this, 'run_cleanup']);
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'pdfm-processor-styles',
            PDFM_PROCESSOR_URL . 'assets/css/processor-styles.css',
            [],
            PDFM_PROCESSOR_VERSION
        );

        wp_register_script(
            'pdfm-processor-scripts',
            PDFM_PROCESSOR_URL . 'assets/js/processor-scripts.js',
            ['jquery'],
            PDFM_PROCESSOR_VERSION,
            true
        );

        wp_localize_script(
            'pdfm-processor-scripts',
            'pdfmProcessor',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('pdfm_processor_nonce'),
            ]
        );
    }

    public function render_shortcode(array $attrs = [], string $content = ''): string
    {
        wp_enqueue_style('pdfm-processor-styles');
        wp_enqueue_script('pdfm-processor-scripts');

        $output  = '<div class="pdfm-processor">';
        $output .= '<form class="pdfm-processor__form" method="post" enctype="multipart/form-data">';
        $output .= wp_nonce_field('pdfm_processor_nonce', '_pdfm_nonce', true, false);
        $output .= '<input type="file" name="pdf_files[]" multiple accept="application/pdf" />';
        $output .= '<select name="operation" class="pdfm-op">'
                . '<option value="compress">' . esc_html__('Compress', 'pdfmaster-processor') . '</option>'
                . '<option value="merge">' . esc_html__('Merge', 'pdfmaster-processor') . '</option>'
                . '</select>';
        // Compression level selector
        $output .= '<div class="pdfm-level-group">';
        $output .= '<label for="pdfm_level" class="pdfm-level-label">' . esc_html__('Compression Level', 'pdfmaster-processor') . '</label>';
        $output .= '<select id="pdfm_level" name="compression_level" class="pdfm-level">';
        $output .= '<option value="low">' . esc_html__('Low Quality - Max Compression (Smaller file, lower quality)', 'pdfmaster-processor') . '</option>';
        $output .= '<option value="medium" selected>' . esc_html__('Medium Quality - Balanced (Recommended)', 'pdfmaster-processor') . '</option>';
        $output .= '<option value="high">' . esc_html__('High Quality - Min Compression (Larger file, better quality)', 'pdfmaster-processor') . '</option>';
        $output .= '</select>';
        $output .= '<p class="pdfm-level-help">' . esc_html__('Lower quality = smaller file size. Medium recommended for most files.', 'pdfmaster-processor') . '</p>';
        $output .= '</div>';
        $output .= '<button type="submit">' . esc_html__('Upload', 'pdfmaster-processor') . '</button>';
        $output .= '</form>';
        $output .= $content;
        $output .= '</div>';

        return $output;
    }

    public function handle_ajax(): void
    {
        check_ajax_referer('pdfm_processor_nonce', 'nonce');

        if (empty($_FILES['pdf_files'])) {
            wp_send_json_error(['message' => __('No files received.', 'pdfmaster-processor')]);
        }

        $operation = sanitize_text_field($_POST['operation'] ?? 'compress');
        // Map user-friendly level to Stirling optimizeLevel (1-9)
        $level_raw = sanitize_text_field($_POST['compression_level'] ?? 'medium');
        $map = [
            'low' => 3,
            'medium' => 5,
            'high' => 7,
        ];
        if (is_numeric($level_raw)) {
            $level = (int) $level_raw;
        } else {
            $level = $map[$level_raw] ?? 5;
        }
        $level = max(1, min(9, $level));

        $stored_files = [];
        foreach ((array) $_FILES['pdf_files']['name'] as $index => $name) {
            $file = [
                'name'     => $name,
                'type'     => $_FILES['pdf_files']['type'][$index] ?? '',
                'tmp_name' => $_FILES['pdf_files']['tmp_name'][$index] ?? '',
                'error'    => $_FILES['pdf_files']['error'][$index] ?? UPLOAD_ERR_OK,
                'size'     => $_FILES['pdf_files']['size'][$index] ?? 0,
            ];

            // Pre-validation for clearer UX messages
            $max_bytes = 104857600; // 100MB
            if (($file['size'] ?? 0) > $max_bytes) {
                $size_h = $this->format_bytes((int) $file['size']);
                wp_send_json_error(['message' => sprintf(__('File too large. Maximum size: 100MB. Your file: %s', 'pdfmaster-processor'), $size_h)]);
            }
            // Check MIME using finfo for reliability
            if (is_readable($file['tmp_name'] ?? '')) {
                $f = finfo_open(FILEINFO_MIME_TYPE);
                $mime = $f ? finfo_file($f, (string) $file['tmp_name']) : '';
                if ($f) finfo_close($f);
                if ($mime !== 'application/pdf') {
                    wp_send_json_error(['message' => __('Only PDF files are supported. Please upload a .pdf file.', 'pdfmaster-processor')]);
                }
            }

            $stored = $this->file_handler->validate_and_persist($file);
            if ($stored instanceof WP_Error) {
                $msg = $stored->get_error_message();
                // Map generic messages to clearer UX copy
                if ($stored->get_error_code() === 'invalid_type') {
                    $msg = __('Only PDF files are supported. Please upload a .pdf file.', 'pdfmaster-processor');
                } elseif ($stored->get_error_code() === 'file_too_large') {
                    $msg = sprintf(__('File too large. Maximum size: 100MB. Your file: %s', 'pdfmaster-processor'), $this->format_bytes((int) $file['size']));
                }
                wp_send_json_error(['message' => $msg]);
            }

            $stored_files[] = $stored;
        }

        if ($stored_files === []) {
            wp_send_json_error(['message' => __('No valid files processed.', 'pdfmaster-processor')]);
        }

        $processed_path = $this->stirling_api->process($stored_files, $operation, $level);

        if ($processed_path instanceof WP_Error) {
            $msg = $processed_path->get_error_message() ?: __('Compression failed. This might be due to a corrupted PDF. Please try another file.', 'pdfmaster-processor');
            wp_send_json_error(['message' => $msg]);
        }

        // Create one-time token and store path in transient for gated download
        $token = wp_generate_password(16, false);
        set_transient('pdfm_file_' . $token, [
            'path' => (string) $processed_path,
            'ts'   => time(),
        ], HOUR_IN_SECONDS);

        $download_url = add_query_arg([
            'action' => 'pdfm_download',
            'token'  => $token,
        ], admin_url('admin-ajax.php'));

        // Calculate size stats for value display
        $original_size = @filesize($stored_files[0]) ?: 0;
        $compressed_size = @filesize((string) $processed_path) ?: 0;
        $reduction = 0;
        if ($original_size > 0 && $compressed_size > 0) {
            $ratio = 1 - ($compressed_size / $original_size);
            $reduction = (int) round($ratio * 100);
            $reduction = max(0, min(99, $reduction));
        }

        wp_send_json_success([
            'token'          => $token,
            'downloadUrl'    => $download_url,
            'download_token' => $token,
            'original_size'  => $this->format_bytes($original_size),
            'compressed_size'=> $this->format_bytes($compressed_size),
            'reduction_percent' => $reduction,
        ]);
    
    }

    private function format_bytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        $kb = $bytes / 1024;
        if ($kb < 1024) {
            return sprintf('%.0f KB', $kb);
        }
        $mb = $kb / 1024;
        if ($mb < 1024) {
            return sprintf('%.1f MB', $mb);
        }
        $gb = $mb / 1024;
        return sprintf('%.1f GB', $gb);
    }

    public function download_file(): void
    {
        $token = sanitize_text_field($_GET['token'] ?? '');
        if ($token === '') {
            wp_die(__('Invalid request', 'pdfmaster-processor'), '', 400);
        }

        $data = get_transient('pdfm_file_' . $token);
        if (! is_array($data) || empty($data['path']) || ! file_exists($data['path'])) {
            wp_die(__('File not available or expired', 'pdfmaster-processor'), '', 404);
        }

        // Verify payment for token
        $paid = get_option('pdfm_paid_token_' . $token, false);
        if (! is_array($paid) || empty($paid['paid'])) {
            wp_die(__('Payment required', 'pdfmaster-processor'), '', 402);
        }

        $path = (string) $data['path'];
        $filename = basename($path);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        // Invalidate token after use
        delete_transient('pdfm_file_' . $token);
        readfile($path);
        exit;
    }

    public function run_cleanup(): void
    {
        $this->cleanup->purge();
    }
}
