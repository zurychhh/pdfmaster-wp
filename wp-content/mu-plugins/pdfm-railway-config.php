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

// Redirect old /test-processor/ URL to /services/ (301 permanent)
// Only runs in production environment (Railway)
add_action('template_redirect', function() {
    // Only redirect in production environment
    if (!defined('WP_ENVIRONMENT_TYPE') || WP_ENVIRONMENT_TYPE !== 'production') {
        return; // Allow /test-processor to work in local/staging
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Redirect any URL containing /test-processor/ to /services/
    if (strpos($request_uri, '/test-processor') !== false) {
        wp_redirect('https://www.pdfspark.app/services/', 301);
        exit;
    }
});
