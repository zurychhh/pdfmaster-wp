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
add_action('template_redirect', function() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Redirect any URL containing /test-processor/ to /services/
    if (strpos($request_uri, '/test-processor') !== false) {
        // For pdfspark.app domain
        if (strpos($_SERVER['HTTP_HOST'] ?? '', 'pdfspark.app') !== false) {
            wp_redirect('https://pdfspark.app/services/', 301);
            exit;
        }
        // For Railway domain (fallback)
        else {
            wp_redirect('https://pdfspark.app/services/', 301);
            exit;
        }
    }
});
