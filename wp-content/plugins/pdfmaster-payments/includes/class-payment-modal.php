<?php
/**
 * Payment modal management.
 */

declare(strict_types=1);

namespace PDFMaster\Payments;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class PaymentModal
{
    public function __construct(
        private readonly StripeHandler $stripe,
        private readonly EmailHandler $emails
    ) {
    }

    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('pdfmaster_payment_modal', [$this, 'render_shortcode']);
        add_action('wp_ajax_pdfm_initiate_payment', [$this, 'handle_payment']);
        add_action('wp_ajax_nopriv_pdfm_initiate_payment', [$this, 'handle_payment']);
    }

    public function enqueue_assets(): void
    {
        // Ensure Stripe.js is available (header)
        wp_enqueue_script(
            'stripe-js',
            'https://js.stripe.com/v3/',
            [],
            null,
            false
        );

        wp_register_style(
            'pdfm-payment-modal',
            PDFM_PAYMENTS_URL . 'assets/css/payment-modal.css',
            [],
            PDFM_PAYMENTS_VERSION
        );

        $script_path = dirname(__DIR__) . '/assets/js/payment-modal.js';
        $script_url  = plugins_url('assets/js/payment-modal.js', dirname(__FILE__));
        $script_ver  = file_exists($script_path) ? filemtime($script_path) : PDFM_PAYMENTS_VERSION;

        wp_register_script(
            'pdfm-payment-modal',
            $script_url,
            ['jquery', 'stripe-js'],
            $script_ver,
            true
        );

        wp_localize_script(
            'pdfm-payment-modal',
            'pdfmPayments',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('pdfm_payments_nonce'),
                'publishableKey' => $this->stripe->get_publishable_key(),
            ]
        );
    }

    public function render_shortcode(array $attrs = [], string $content = ''): string
    {
        wp_enqueue_style('pdfm-payment-modal');
        wp_enqueue_script('pdfm-payment-modal');

        $template = PDFM_PAYMENTS_PATH . 'templates/payment-modal.php';

        if (! file_exists($template)) {
            return '';
        }

        $publishable_key = $this->stripe->get_publishable_key();

        ob_start();
        include $template;

        return ob_get_clean() . $content;
    }

    public function handle_payment(): void
    {
        check_ajax_referer('pdfm_payments_nonce', 'nonce');

        $file_token = sanitize_text_field((string) ($_POST['file_token'] ?? ''));
        if ($file_token === '') {
            wp_send_json_error(['message' => __('Missing file_token', 'pdfmaster-payments')]);
        }

        $result = $this->stripe->create_payment_intent($file_token);

        if ($result instanceof WP_Error) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        // TODO: Persist payment intent details and confirm payment webhook.
        wp_send_json_success(['intent' => $result]);
    }
}
