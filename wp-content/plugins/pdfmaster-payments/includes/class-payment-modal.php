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
        private readonly CreditsManager $credits,
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
        wp_register_style(
            'pdfm-payment-modal',
            PDFM_PAYMENTS_URL . 'assets/css/payment-modal.css',
            [],
            PDFM_PAYMENTS_VERSION
        );

        wp_register_script(
            'pdfm-payment-modal',
            PDFM_PAYMENTS_URL . 'assets/js/payment-modal.js',
            ['jquery'],
            PDFM_PAYMENTS_VERSION,
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

        $credits = $this->credits;
        $publishable_key = $this->stripe->get_publishable_key();

        ob_start();
        include $template;

        return ob_get_clean() . $content;
    }

    public function handle_payment(): void
    {
        check_ajax_referer('pdfm_payments_nonce', 'nonce');

        $current_user = wp_get_current_user();
        if (! $current_user || 0 === $current_user->ID) {
            wp_send_json_error(['message' => __('Authentication required for purchase.', 'pdfmaster-payments')], 401);
        }

        $credits = max(1, (int) ($_POST['credits'] ?? 0));
        $result = $this->stripe->create_payment_intent($credits);

        if ($result instanceof WP_Error) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        // TODO: Persist payment intent details and confirm payment webhook.
        wp_send_json_success(['intent' => $result]);
    }
}
