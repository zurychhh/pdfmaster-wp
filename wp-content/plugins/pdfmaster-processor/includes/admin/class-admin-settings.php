<?php
/**
 * Admin settings for PDFMaster Processor (autoloaded).
 */

declare(strict_types=1);

namespace PDFMaster\Processor\Admin;

if (! defined('ABSPATH')) {
    exit;
}

class AdminSettings
{
    public function register_settings_page(): void
    {
        add_options_page(
            'PDFMaster Processor Settings',
            'PDFMaster Processor',
            'manage_options',
            'pdfmaster-processor-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings(): void
    {
        register_setting('pdfm_processor_settings', 'pdfm_stirling_endpoint', [
            'type'              => 'string',
            'default'           => 'http://localhost:8080',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        register_setting('pdfm_processor_settings', 'pdfm_stirling_timeout', [
            'type'              => 'integer',
            'default'           => 30,
            'sanitize_callback' => 'absint',
        ]);

        register_setting('pdfm_processor_settings', 'pdfm_max_file_size', [
            'type'              => 'integer',
            'default'           => 104857600,
            'sanitize_callback' => 'absint',
        ]);
    }

    public function render_settings_page(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        include plugin_dir_path(__FILE__) . 'views/admin-settings.php';
    }

    public function test_connection(): void
    {
        check_ajax_referer('pdfm_test_connection', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $endpoint = get_option('pdfm_stirling_endpoint', 'http://localhost:8080');
        $response = wp_remote_get(trailingslashit($endpoint) . 'api/v1/info/status', [
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        if ($code === 200) {
            wp_send_json_success(['message' => 'Connection successful!']);
        }

        wp_send_json_error(['message' => 'HTTP ' . $code . ' - Check endpoint URL']);
    }
}
