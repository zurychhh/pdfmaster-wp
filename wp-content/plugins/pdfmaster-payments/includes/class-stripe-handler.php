<?php
/**
 * Stripe API handler stub.
 */

declare(strict_types=1);

namespace PDFMaster\Payments;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class StripeHandler
{
    private string $publishable_key;
    private string $secret_key;
    private string $webhook_secret;

    public function __construct()
    {
        $this->publishable_key = (string) apply_filters('pdfm_stripe_publishable_key', '');
        $this->secret_key = (string) apply_filters('pdfm_stripe_secret_key', '');

        // Get webhook secret based on mode (test/live)
        $options = get_option('pdfm_stripe_settings', []);
        $mode = $options['mode'] ?? 'test';

        if ($mode === 'live') {
            $this->webhook_secret = (string) ($options['live_webhook_secret'] ?? '');
        } else {
            // Test mode - try new key, fallback to legacy
            $this->webhook_secret = (string) ($options['test_webhook_secret'] ?? $options['webhook_secret'] ?? '');
        }
    }

    public function register_hooks(): void
    {
        add_action('wp_ajax_pdfm_create_payment_intent', [$this, 'ajax_create_payment_intent']);
        add_action('wp_ajax_nopriv_pdfm_create_payment_intent', [$this, 'ajax_create_payment_intent']);
        add_action('wp_ajax_pdfm_confirm_payment', [$this, 'ajax_confirm_payment']);
        add_action('wp_ajax_nopriv_pdfm_confirm_payment', [$this, 'ajax_confirm_payment']);

        add_action('rest_api_init', function () {
            register_rest_route('pdfmaster/v1', '/stripe-webhook', [
                'methods' => 'POST',
                'callback' => [$this, 'handle_webhook'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    /**
     * Create a Stripe payment intent for $1.99 pay-per-action.
     */
    public function create_payment_intent(string $file_token): array|WP_Error
    {
        if ($this->secret_key === '') {
            return new WP_Error('pdfm_stripe_keys_missing', __('Stripe keys not configured.', 'pdfmaster-payments'));
        }

        try {
            \Stripe\Stripe::setApiKey($this->secret_key);

            $amount = 199; // $1.99 per action

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'file_token' => $file_token,
                ],
            ]);

            return [
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
                'amount' => $amount,
                'currency' => 'usd',
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return new WP_Error('stripe_api_error', 'Stripe API Error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            return new WP_Error('stripe_exception', 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function get_publishable_key(): string
    {
        return $this->publishable_key;
    }

    public function ajax_create_payment_intent(): void
    {
        try {
            check_ajax_referer('pdfm_payments_nonce', 'nonce');
            $file_token = sanitize_text_field((string) ($_POST['file_token'] ?? ''));
            if ($file_token === '') {
                wp_send_json_error(['message' => __('Missing file_token', 'pdfmaster-payments')]);
            }

            // Debug: Check if keys are loaded
            if ($this->secret_key === '') {
                wp_send_json_error([
                    'message' => 'Stripe secret key is empty',
                    'debug' => [
                        'pub_key_length' => strlen($this->publishable_key),
                        'secret_key_length' => strlen($this->secret_key),
                    ]
                ]);
            }

            $result = $this->create_payment_intent($file_token);
            if ($result instanceof WP_Error) {
                wp_send_json_error(['message' => $result->get_error_message()]);
            }
            wp_send_json_success($result);
        } catch (\Throwable $e) {
            wp_send_json_error([
                'message' => 'Exception: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function ajax_confirm_payment(): void
    {
        check_ajax_referer('pdfm_payments_nonce', 'nonce');
        $intent_id = sanitize_text_field((string) ($_POST['payment_intent_id'] ?? ''));
        $file_token = sanitize_text_field((string) ($_POST['file_token'] ?? ''));
        if ($intent_id === '' || $file_token === '') {
            wp_send_json_error(['message' => __('Missing payment_intent_id', 'pdfmaster-payments')]);
        }

        if ($this->secret_key === '') {
            wp_send_json_error(['message' => __('Stripe keys not configured.', 'pdfmaster-payments')]);
        }

        \Stripe\Stripe::setApiKey($this->secret_key);
        $intent = \Stripe\PaymentIntent::retrieve($intent_id);
        if ($intent->status !== 'succeeded') {
            wp_send_json_error(['message' => sprintf(__('Payment not successful (status: %s)', 'pdfmaster-payments'), $intent->status)]);
        }

        // Mark token as paid
        update_option('pdfm_paid_token_' . $file_token, [
            'paid' => true,
            'payment_intent' => $intent_id,
            'timestamp' => time(),
        ], false);

        // Send receipt email if available
        $email = (string) ($intent->receipt_email ?? '');
        if ($email !== '') {
            $this->send_receipt_email($email, $file_token);
        }

        wp_send_json_success([
            'message' => __('Payment successful! Your file is ready to download.', 'pdfmaster-payments'),
            'file_token' => $file_token,
        ]);
    }

    public function handle_webhook(\WP_REST_Request $request): \WP_REST_RESPONSE
    {
        $payload = $request->get_body();
        $sig = $request->get_header('stripe-signature');
        if ($this->webhook_secret === '') {
            return new \WP_REST_Response('Webhook secret not configured', 400);
        }
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $this->webhook_secret);
        } catch (\Throwable $e) {
            return new \WP_REST_Response($e->getMessage(), 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data['object'];
            $file_token = (string) ($intent['metadata']['file_token'] ?? '');
            if ($file_token !== '') {
                update_option('pdfm_paid_token_' . $file_token, [
                    'paid' => true,
                    'payment_intent' => $intent['id'] ?? '',
                    'timestamp' => time(),
                ], false);
                $email = (string) ($intent['receipt_email'] ?? '');
                if ($email !== '') {
                    $this->send_receipt_email($email, $file_token);
                }
            }
        }

        return new \WP_REST_Response('ok', 200);
    }

    private function send_receipt_email(string $email, string $file_token): void
    {
        if ($email === '') {
            return;
        }
        $download_url = add_query_arg([
            'action' => 'pdfm_download',
            'token'  => $file_token,
        ], admin_url('admin-ajax.php'));
        $subject = __('Your PDF is ready - receipt', 'pdfmaster-payments');
        $message = sprintf("%s\n\n%s\n%s: %s\n",
            __('Thanks for your purchase!', 'pdfmaster-payments'),
            __('Your file has been processed and is ready to download. Amount paid: $1.99', 'pdfmaster-payments'),
            __('Download link (valid for a limited time)', 'pdfmaster-payments'),
            $download_url
        );
        wp_mail($email, $subject, $message);
    }
}
