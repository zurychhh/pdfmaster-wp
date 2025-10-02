<?php
/**
 * Plugin Name: PDFMaster Payments
 * Plugin URI: https://pdfmaster.example.com
 * Description: Stripe payments and credit management system for the PDFMaster application.
 * Version: 0.1.0
 * Author: PDFMaster
 * Author URI: https://pdfmaster.example.com
 * Text Domain: pdfmaster-payments
 * Requires PHP: 8.1
 * Requires at least: 6.4
 */

declare(strict_types=1);

namespace PDFMaster\Payments;

require_once __DIR__ . '/autoload.php';

if (! defined('ABSPATH')) {
    exit;
}

const PDFM_PAYMENTS_VERSION = '0.1.0';
const PDFM_PAYMENTS_PATH = __DIR__ . '/';

if (! defined('PDFM_PAYMENTS_URL')) {
    define('PDFM_PAYMENTS_URL', plugin_dir_url(__FILE__));
}

/**
 * Load plugin text domain.
 */
function pdfm_load_textdomain(): void
{
    load_plugin_textdomain('pdfmaster-payments', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', __NAMESPACE__ . '\\pdfm_load_textdomain');

/**
 * Bootstrap payment services.
 */
function pdfm_bootstrap(): void
{
    $stripe = new StripeHandler();
    $credits = new CreditsManager();
    $emails = new EmailHandler();
    $modal = new PaymentModal($stripe, $credits, $emails);

    $modal->register_hooks();
}
add_action('plugins_loaded', __NAMESPACE__ . '\\pdfm_bootstrap', 20);
