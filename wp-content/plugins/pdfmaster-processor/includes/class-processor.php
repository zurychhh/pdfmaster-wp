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

        $stored_files = [];
        foreach ((array) $_FILES['pdf_files']['name'] as $index => $name) {
            $file = [
                'name'     => $name,
                'type'     => $_FILES['pdf_files']['type'][$index] ?? '',
                'tmp_name' => $_FILES['pdf_files']['tmp_name'][$index] ?? '',
                'error'    => $_FILES['pdf_files']['error'][$index] ?? UPLOAD_ERR_OK,
                'size'     => $_FILES['pdf_files']['size'][$index] ?? 0,
            ];

            $stored = $this->file_handler->validate_and_persist($file);
            if ($stored instanceof WP_Error) {
                wp_send_json_error(['message' => $stored->get_error_message()]);
            }

            $stored_files[] = $stored;
        }

        if ($stored_files === []) {
            wp_send_json_error(['message' => __('No valid files processed.', 'pdfmaster-processor')]);
        }

        $processed_path = $this->stirling_api->process($stored_files, $operation);

        if ($processed_path instanceof WP_Error) {
            wp_send_json_error(['message' => $processed_path->get_error_message()]);
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

        $credits = 0;
        if (class_exists('PDFMaster\\Payments\\CreditsManager')) {
            $credits = (new \PDFMaster\Payments\CreditsManager())->get_user_credits(get_current_user_id());
        }

        wp_send_json_success([
            'token'        => $token,
            'downloadUrl'  => $download_url,
            'credits'      => $credits,
            'canDownload'  => $credits > 0,
            'message'      => $credits > 0 ? __('You have credits. You can download now.', 'pdfmaster-processor') : __('Insufficient credits. Please purchase to download.', 'pdfmaster-processor'),
        ]);
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

        // Verify credits
        $user_id = get_current_user_id();
        if (! class_exists('PDFMaster\\Payments\\CreditsManager')) {
            wp_die(__('Payments not configured', 'pdfmaster-processor'), '', 403);
        }

        $credits = new \PDFMaster\Payments\CreditsManager();
        if (! $credits->deduct_credits($user_id, 1)) {
            wp_die(__('Not enough credits', 'pdfmaster-processor'), '', 402);
        }

        // Stream file
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
