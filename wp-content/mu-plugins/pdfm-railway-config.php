<?php
/**
 * PDFMaster Railway Configuration
 *
 * Must-use plugin to override Stripe keys from Railway environment variables.
 * This runs before regular plugins, ensuring env vars take precedence.
 */

// Stripe configuration from environment variables (override database settings)
add_filter('pdfm_stripe_publishable_key', function($value) {
    if (!getenv('RAILWAY_ENVIRONMENT')) {
        return $value;
    }

    $env_key = getenv('STRIPE_PUBLISHABLE_KEY');
    return $env_key !== false && $env_key !== '' ? $env_key : $value;
}, 20);

add_filter('pdfm_stripe_secret_key', function($value) {
    if (!getenv('RAILWAY_ENVIRONMENT')) {
        return $value;
    }

    $env_key = getenv('STRIPE_SECRET_KEY');
    return $env_key !== false && $env_key !== '' ? $env_key : $value;
}, 20);
