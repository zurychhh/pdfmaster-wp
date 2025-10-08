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
        $this->webhook_secret = (string) (get_option('pdfm_stripe_settings')['webhook_secret'] ?? '');
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
     * Create a Stripe payment intent.
     */
    public function create_payment_intent(int $credits): array|WP_Error
    {
        if ($this->secret_key === '') {
            return new WP_Error('pdfm_stripe_keys_missing', __('Stripe keys not configured.', 'pdfmaster-payments'));
        }

        \Stripe\Stripe::setApiKey($this->secret_key);

        $amount = 290; // $2.90 for 3 credits

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'credits' => (string) $credits,
            ],
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
            'amount' => $amount,
            'currency' => 'usd',
            'credits' => $credits,
        ];
    }

    public function get_publishable_key(): string
    {
        return $this->publishable_key;
    }

    public function ajax_create_payment_intent(): void
    {
        check_ajax_referer('pdfm_payments_nonce', 'nonce');
        $credits = max(1, (int) ($_POST['credits'] ?? 3));
        $result = $this->create_payment_intent($credits);
        if ($result instanceof WP_Error) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        wp_send_json_success($result);
    }

    public function ajax_confirm_payment(): void
    {
        check_ajax_referer('pdfm_payments_nonce', 'nonce');
        $intent_id = sanitize_text_field((string) ($_POST['payment_intent_id'] ?? ''));
        if ($intent_id === '') {
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

        $credits = (int) ($intent->metadata['credits'] ?? 3);
        $user_id = get_current_user_id();
        if ($user_id) {
            (new CreditsManager())->add_credits($user_id, $credits);
        }

        wp_send_json_success(['balance' => (new CreditsManager())->get_user_credits($user_id)]);
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
            $credits = (int) ($intent['metadata']['credits'] ?? 3);
            $user_id = get_current_user_id();
            if ($user_id) {
                (new CreditsManager())->add_credits($user_id, $credits);
            }
        }

        return new \WP_REST_Response('ok', 200);
    }
}
