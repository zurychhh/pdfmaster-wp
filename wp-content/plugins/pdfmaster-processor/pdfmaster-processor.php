<?php
/**
 * Plugin Name: PDFMaster Processor
 * Plugin URI: https://pdfmaster.example.com
 * Description: Handles PDF upload, validation, processing, and cleanup for PDFMaster via the Stirling PDF API.
 * Version: 0.1.0
 * Author: PDFMaster
 * Author URI: https://pdfmaster.example.com
 * Text Domain: pdfmaster-processor
 * Requires PHP: 8.1
 * Requires at least: 6.4
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

require_once __DIR__ . '/autoload.php';

if (! defined('ABSPATH')) {
    exit;
}

const PDFM_PROCESSOR_VERSION = '0.1.0';
const PDFM_PROCESSOR_PATH = __DIR__ . '/';

if (! defined('PDFM_PROCESSOR_URL')) {
    define('PDFM_PROCESSOR_URL', plugin_dir_url(__FILE__));
}

/**
 * Load plugin text domain.
 */
function pdfm_load_textdomain(): void
{
    load_plugin_textdomain('pdfmaster-processor', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', __NAMESPACE__ . '\\pdfm_load_textdomain');

/**
 * Initialise processor services.
 */
function pdfm_bootstrap(): void
{
    $file_handler = new FileHandler();
    $stirling_api = new StirlingApi();
    $cleanup = new Cleanup($file_handler);

    $processor = new Processor($file_handler, $stirling_api, $cleanup);
    $processor->register_hooks();

    // Ensure admin settings page is registered in WP Admin
    if (is_admin()) {
        require_once __DIR__ . '/includes/class-admin-bootstrap.php';
    }
}
add_action('plugins_loaded', __NAMESPACE__ . '\\pdfm_bootstrap', 20);

/**
 * Activate the plugin.
 */
function pdfm_activate(): void
{
    if (! wp_next_scheduled('pdfm_processor_cleanup_cron')) {
        wp_schedule_event(time(), 'hourly', 'pdfm_processor_cleanup_cron');
    }
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\pdfm_activate');

/**
 * Deactivate the plugin.
 */
function pdfm_deactivate(): void
{
    wp_clear_scheduled_hook('pdfm_processor_cleanup_cron');
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\pdfm_deactivate');
