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

        // Hero Section
        $output .= '<div class="pdfm-hero">';
        $output .= '  <h1 class="pdfm-hero-title">' . esc_html__('PDF Tools', 'pdfmaster-processor') . '</h1>';
        $output .= '  <p class="pdfm-hero-subtitle">' . esc_html__('Professional PDF processing in seconds. $1.99 per action, no subscription required.', 'pdfmaster-processor') . '</p>';
        $output .= '</div>';

        $output .= '<form class="pdfm-processor__form" method="post" enctype="multipart/form-data">';
        $output .= wp_nonce_field('pdfm_processor_nonce', '_pdfm_nonce', true, false);

        // Service Tabs with descriptions
        $output .= '<div class="pdfm-service-tabs">';
        $output .= '<label class="pdfm-tab active" data-operation="compress">';
        $output .= '<input type="radio" name="operation" value="compress" checked>';
        $output .= '<div class="pdfm-tab-label">' . esc_html__('Compress Spark', 'pdfmaster-processor') . '</div>';
        $output .= '<div class="pdfm-tab-desc">' . esc_html__('Reduce file size by up to 90%', 'pdfmaster-processor') . '</div>';
        $output .= '</label>';
        $output .= '<label class="pdfm-tab" data-operation="merge">';
        $output .= '<input type="radio" name="operation" value="merge">';
        $output .= '<div class="pdfm-tab-label">' . esc_html__('Merge Spark', 'pdfmaster-processor') . '</div>';
        $output .= '<div class="pdfm-tab-desc">' . esc_html__('Combine multiple PDFs into one', 'pdfmaster-processor') . '</div>';
        $output .= '</label>';
        $output .= '<label class="pdfm-tab" data-operation="split">';
        $output .= '<input type="radio" name="operation" value="split">';
        $output .= '<div class="pdfm-tab-label">' . esc_html__('Split Spark', 'pdfmaster-processor') . '</div>';
        $output .= '<div class="pdfm-tab-desc">' . esc_html__('Extract specific pages from PDF', 'pdfmaster-processor') . '</div>';
        $output .= '</label>';
        $output .= '<label class="pdfm-tab" data-operation="convert">';
        $output .= '<input type="radio" name="operation" value="convert">';
        $output .= '<div class="pdfm-tab-label">' . esc_html__('Convert Spark', 'pdfmaster-processor') . '</div>';
        $output .= '<div class="pdfm-tab-desc">' . esc_html__('Images ↔ PDF conversion', 'pdfmaster-processor') . '</div>';
        $output .= '</label>';
        $output .= '</div>';

        // File upload section
        $output .= '<div class="pdfm-file-upload">';
        $output .= '  <input type="file" id="pdfm-file-input" accept="application/pdf" style="display:none" />';
        $output .= '  <button type="button" class="pdfm-add-file button">' . esc_html__('Add PDF File', 'pdfmaster-processor') . '</button>';
        $output .= '  <div class="pdfm-file-list"></div>';
        $output .= '</div>';

        // Conditional help text
        $output .= '<p class="pdfm-help" data-for="compress">' . esc_html__('Add 1 PDF file (max 100MB)', 'pdfmaster-processor') . '</p>';
        $output .= '<p class="pdfm-help" data-for="merge" style="display:none">' . esc_html__('Add 2-10 PDF files (max 100MB each)', 'pdfmaster-processor') . '</p>';
        $output .= '<p class="pdfm-help" data-for="split" style="display:none">' . esc_html__('Add 1 PDF file to extract pages from', 'pdfmaster-processor') . '</p>';
        $output .= '<p class="pdfm-help" data-for="convert" style="display:none">' . esc_html__('See conversion options below', 'pdfmaster-processor') . '</p>';

        // Compression level selector (show only for compress)
        $output .= '<div class="pdfm-level-group" data-show-for="compress">';
        $output .= '<label for="pdfm_level" class="pdfm-level-label">' . esc_html__('Compression Level', 'pdfmaster-processor') . '</label>';
        $output .= '<select id="pdfm_level" name="compression_level" class="pdfm-level">';
        $output .= '<option value="low">' . esc_html__('Low Quality - Max Compression (Smaller file, lower quality)', 'pdfmaster-processor') . '</option>';
        $output .= '<option value="medium" selected>' . esc_html__('Medium Quality - Balanced (Recommended)', 'pdfmaster-processor') . '</option>';
        $output .= '<option value="high">' . esc_html__('High Quality - Min Compression (Larger file, better quality)', 'pdfmaster-processor') . '</option>';
        $output .= '</select>';
        $output .= '<p class="pdfm-level-help">' . esc_html__('Lower quality = smaller file size. Medium recommended for most files.', 'pdfmaster-processor') . '</p>';
        $output .= '</div>';

        // Page range input (show only for split)
        $output .= '<div class="pdfm-pages-group" style="display:none" data-show-for="split">';
        $output .= '  <label for="pdfm_pages" class="pdfm-pages-label">' . esc_html__('Page Numbers', 'pdfmaster-processor') . '</label>';
        $output .= '  <input type="text" id="pdfm_pages" name="pages" class="pdfm-pages" placeholder="' . esc_attr__('e.g., 1-5 or 1,3,5-7', 'pdfmaster-processor') . '" />';
        $output .= '  <p class="pdfm-pages-help">' . esc_html__('Enter page numbers or ranges. Examples: "1-5" or "1,3,5-7"', 'pdfmaster-processor') . '</p>';
        $output .= '</div>';

        // Convert direction selector (show only for convert)
        $output .= '<div class="pdfm-convert-group" style="display:none" data-show-for="convert">';
        $output .= '  <label class="pdfm-convert-label">' . esc_html__('Conversion Direction', 'pdfmaster-processor') . '</label>';
        $output .= '  <div class="pdfm-convert-direction">';
        $output .= '    <label class="pdfm-direction-option active">';
        $output .= '      <input type="radio" name="convert_direction" value="img-to-pdf" checked>';
        $output .= '      <span>Images → PDF</span>';
        $output .= '    </label>';
        $output .= '    <label class="pdfm-direction-option">';
        $output .= '      <input type="radio" name="convert_direction" value="pdf-to-img">';
        $output .= '      <span>PDF → Images</span>';
        $output .= '    </label>';
        $output .= '  </div>';
        $output .= '  <div class="pdfm-format-group" style="display:none" data-for="pdf-to-img">';
        $output .= '    <label for="pdfm_format">' . esc_html__('Image Format', 'pdfmaster-processor') . '</label>';
        $output .= '    <select id="pdfm_format" name="format">';
        $output .= '      <option value="jpg" selected>JPG</option>';
        $output .= '      <option value="png">PNG</option>';
        $output .= '    </select>';
        $output .= '  </div>';
        $output .= '  <p class="pdfm-convert-help" data-for="img-to-pdf">' . esc_html__('Upload 1-10 images (JPG, PNG, BMP)', 'pdfmaster-processor') . '</p>';
        $output .= '  <p class="pdfm-convert-help" data-for="pdf-to-img" style="display:none">' . esc_html__('Upload 1 PDF to extract images from', 'pdfmaster-processor') . '</p>';
        $output .= '</div>';

        $output .= '<button type="submit">' . esc_html__('Process PDF', 'pdfmaster-processor') . '</button>';
        $output .= '</form>';

        // Success state (initially hidden, shown after successful compression)
        $output .= '<div id="pdfm-success-state" class="pdfm-success-container" style="display:none;">';
        $output .= '  <div class="pdfm-success-icon">';
        $output .= '    <svg viewBox="0 0 24 24" class="pdfm-checkmark" aria-hidden="true">';
        $output .= '      <polyline points="20 6 9 17 4 12"></polyline>';
        $output .= '    </svg>';
        $output .= '  </div>';
        $output .= '  <h2 class="pdfm-success-title">' . esc_html__('Compression Successful!', 'pdfmaster-processor') . '</h2>';
        $output .= '  <p class="pdfm-success-subtitle">' . esc_html__('Your PDF has been optimized and is ready to download', 'pdfmaster-processor') . '</p>';
        $output .= '  <div class="pdfm-stats-card">';
        $output .= '    <div class="pdfm-stat-row">';
        $output .= '      <span class="pdfm-stat-label">' . esc_html__('Original Size', 'pdfmaster-processor') . '</span>';
        $output .= '      <span class="pdfm-stat-value" id="pdfm-original-size"></span>';
        $output .= '    </div>';
        $output .= '    <div class="pdfm-divider"></div>';
        $output .= '    <div class="pdfm-stat-row">';
        $output .= '      <span class="pdfm-stat-label">' . esc_html__('Compressed Size', 'pdfmaster-processor') . '</span>';
        $output .= '      <span class="pdfm-stat-value pdfm-stat-green" id="pdfm-compressed-size"></span>';
        $output .= '    </div>';
        $output .= '    <div class="pdfm-divider"></div>';
        $output .= '    <div class="pdfm-stat-row">';
        $output .= '      <span class="pdfm-stat-label">' . esc_html__('Space Saved', 'pdfmaster-processor') . '</span>';
        $output .= '      <span class="pdfm-stat-improvement" id="pdfm-improvement"></span>';
        $output .= '    </div>';
        $output .= '  </div>';
        $output .= '  <button class="pdfm-btn pdfm-btn-primary" id="pdfm-pay-button" type="button">';
        $output .= esc_html__('Pay $1.99 to Download', 'pdfmaster-processor');
        $output .= '  </button>';
        $output .= '  <button class="pdfm-btn pdfm-btn-secondary" id="pdfm-reset-button" type="button">';
        $output .= esc_html__('Process Another File', 'pdfmaster-processor');
        $output .= '  </button>';
        $output .= '  <div class="pdfm-trust-signal">';
        $output .= '    <svg viewBox="0 0 24 24" class="pdfm-lock-icon" aria-hidden="true">';
        $output .= '      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>';
        $output .= '      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>';
        $output .= '    </svg>';
        $output .= '    <span>' . esc_html__('Your file will be automatically deleted after 1 hour', 'pdfmaster-processor') . '</span>';
        $output .= '  </div>';
        $output .= '</div>';

        // Generic Success State (for merge, split, convert - no stats card)
        $output .= '<div id="pdfm-success-state-generic" class="pdfm-success-container" style="display:none;">';
        $output .= '  <div class="pdfm-success-icon">';
        $output .= '    <svg viewBox="0 0 24 24" class="pdfm-checkmark" aria-hidden="true">';
        $output .= '      <polyline points="20 6 9 17 4 12"></polyline>';
        $output .= '    </svg>';
        $output .= '  </div>';
        $output .= '  <h2 class="pdfm-success-title" id="pdfm-generic-title"></h2>';
        $output .= '  <p class="pdfm-success-subtitle" id="pdfm-generic-subtitle"></p>';
        $output .= '  <button class="pdfm-btn pdfm-btn-primary" id="pdfm-pay-button-generic" type="button">';
        $output .= esc_html__('Pay $1.99 to Download', 'pdfmaster-processor');
        $output .= '  </button>';
        $output .= '  <button class="pdfm-btn pdfm-btn-secondary" id="pdfm-reset-button-generic" type="button">';
        $output .= esc_html__('Process Another File', 'pdfmaster-processor');
        $output .= '  </button>';
        $output .= '  <div class="pdfm-trust-signal">';
        $output .= '    <svg viewBox="0 0 24 24" class="pdfm-lock-icon" aria-hidden="true">';
        $output .= '      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>';
        $output .= '      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>';
        $output .= '    </svg>';
        $output .= '    <span>' . esc_html__('Your file will be automatically deleted after 1 hour', 'pdfmaster-processor') . '</span>';
        $output .= '  </div>';
        $output .= '</div>';

        // Download Success State (shown after payment success)
        $output .= '<div class="pdfm-download-success-state" style="display:none;">';
        $output .= '  <div class="pdfm-success-container">';
        $output .= '    <div class="pdfm-success-checkmark">';
        $output .= '      <svg viewBox="0 0 24 24" class="pdfm-checkmark-icon" aria-hidden="true">';
        $output .= '        <polyline points="20 6 9 17 4 12"></polyline>';
        $output .= '      </svg>';
        $output .= '    </div>';
        $output .= '    <h2 class="pdfm-success-title">' . esc_html__('Payment Successful!', 'pdfmaster-processor') . '</h2>';
        $output .= '    <p class="pdfm-success-subtitle">' . esc_html__('Your compressed PDF is ready to download.', 'pdfmaster-processor') . '</p>';
        $output .= '    <button id="pdfm-download-final" class="pdfm-btn-download-final" type="button">';
        $output .= '      <svg viewBox="0 0 24 24" class="pdfm-download-icon" aria-hidden="true">';
        $output .= '        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>';
        $output .= '        <polyline points="7 10 12 15 17 10"></polyline>';
        $output .= '        <line x1="12" y1="15" x2="12" y2="3"></line>';
        $output .= '      </svg>';
        $output .= esc_html__('Download Your PDF', 'pdfmaster-processor');
        $output .= '    </button>';
        $output .= '    <button id="pdfm-process-another-final" class="pdfm-btn-secondary-final" type="button">';
        $output .= esc_html__('Process Another File', 'pdfmaster-processor');
        $output .= '    </button>';
        $output .= '    <div class="pdfm-auto-delete-notice">';
        $output .= '      <svg viewBox="0 0 24 24" class="pdfm-notice-icon" aria-hidden="true">';
        $output .= '        <circle cx="12" cy="12" r="10"></circle>';
        $output .= '        <line x1="12" y1="8" x2="12" y2="12"></line>';
        $output .= '        <line x1="12" y1="16" x2="12.01" y2="16"></line>';
        $output .= '      </svg>';
        $output .= '      <span>' . esc_html__('Your file will be automatically deleted from our servers in 1 hour for your security.', 'pdfmaster-processor') . '</span>';
        $output .= '    </div>';
        $output .= '  </div>';
        $output .= '</div>';

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
        $pages = sanitize_text_field($_POST['pages'] ?? '');
        $format = sanitize_text_field($_POST['format'] ?? 'jpg');

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

                // Validate based on operation
                if ($operation === 'img-to-pdf') {
                    $valid_image_types = ['image/jpeg', 'image/png', 'image/bmp'];
                    if (! in_array($mime, $valid_image_types, true)) {
                        wp_send_json_error(['message' => __('Images to PDF: Only JPG, PNG, and BMP files supported.', 'pdfmaster-processor')]);
                    }
                } else {
                    // All other operations require PDF
                    if ($mime !== 'application/pdf') {
                        wp_send_json_error(['message' => __('Only PDF files are supported. Please upload a .pdf file.', 'pdfmaster-processor')]);
                    }
                }
            }

            $stored = $this->file_handler->validate_and_persist($file);
            if ($stored instanceof WP_Error) {
                wp_send_json_error(['message' => $stored->get_error_message()]);
            }

            $stored_files[] = $stored;
        }

        if ($stored_files === []) {
            wp_send_json_error(['message' => __('No valid files processed.', 'pdfmaster-processor')]);
        }

        $processed_path = $this->stirling_api->process($stored_files, $operation, $level, $pages, $format);

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

        // Detect content type
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $content_type = 'application/pdf';
        if ($extension === 'zip') {
            $content_type = 'application/zip';
        }

        header('Content-Type: ' . $content_type);
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
