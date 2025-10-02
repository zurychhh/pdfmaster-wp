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

        // TODO: Replace placeholder markup with Elementor-powered UI integration.
        $output  = '<div class="pdfm-processor">';
        $output .= '<form class="pdfm-processor__form" method="post" enctype="multipart/form-data">';
        $output .= wp_nonce_field('pdfm_processor_nonce', '_pdfm_nonce', true, false);
        $output .= '<input type="file" name="pdf_files[]" multiple accept="application/pdf" />';
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

        $stored_files = [];
        foreach ((array) $_FILES['pdf_files']['name'] as $index => $name) {
            $file = [
                'name'     => $name,
                'type'     => $_FILES['pdf_files']['type'][$index] ?? '',
                'tmp_name' => $_FILES['pdf_files']['tmp_name'][$index] ?? '',
                'error'    => $_FILES['pdf_files']['error'][$index] ?? UPLOAD_ERR_OK,
                'size'     => $_FILES['pdf_files']['size'][$index] ?? 0,
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                continue;
            }

            $validated = $this->file_handler->validate($file);
            if ($validated instanceof WP_Error) {
                wp_send_json_error(['message' => $validated->get_error_message()]);
            }

            $stored = $this->file_handler->persist($file);
            if ($stored instanceof WP_Error) {
                wp_send_json_error(['message' => $stored->get_error_message()]);
            }

            $stored_files[] = $stored;
        }

        if ($stored_files === []) {
            wp_send_json_error(['message' => __('No valid files processed.', 'pdfmaster-processor')]);
        }

        $response = $this->stirling_api->process($stored_files, sanitize_text_field($_POST['operation'] ?? 'compress'));

        if ($response instanceof WP_Error) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        wp_send_json_success(['data' => $response]);
    }

    public function run_cleanup(): void
    {
        $this->cleanup->purge();
    }
}
