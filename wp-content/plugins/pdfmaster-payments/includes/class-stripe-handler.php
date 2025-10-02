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

    public function __construct()
    {
        $this->publishable_key = (string) apply_filters('pdfm_stripe_publishable_key', '');
        $this->secret_key = (string) apply_filters('pdfm_stripe_secret_key', '');
    }

    /**
     * Create a Stripe payment intent.
     */
    public function create_payment_intent(int $credits): array|WP_Error
    {
        // TODO: Implement Stripe PHP SDK integration.
        unset($credits);

        return new WP_Error('pdfm_stripe_stub', __('Stripe integration pending configuration.', 'pdfmaster-payments'));
    }

    public function get_publishable_key(): string
    {
        return $this->publishable_key;
    }
}
