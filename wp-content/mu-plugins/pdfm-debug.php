<?php
/**
 * Temporary debug endpoint for Stripe configuration
 * DELETE THIS FILE AFTER DEBUGGING
 */

add_action('init', function() {
    if (isset($_GET['pdfm_debug']) && $_GET['pdfm_debug'] === 'stripe_config') {
        header('Content-Type: application/json');

        $debug_info = [
            'railway_env' => getenv('RAILWAY_ENVIRONMENT'),
            'stripe_pub_env' => getenv('STRIPE_PUBLISHABLE_KEY') ? 'SET (length: ' . strlen(getenv('STRIPE_PUBLISHABLE_KEY')) . ')' : 'NOT SET',
            'stripe_secret_env' => getenv('STRIPE_SECRET_KEY') ? 'SET (length: ' . strlen(getenv('STRIPE_SECRET_KEY')) . ')' : 'NOT SET',
            'filter_pub_key' => apply_filters('pdfm_stripe_publishable_key', 'DEFAULT'),
            'filter_secret_key' => apply_filters('pdfm_stripe_secret_key', 'DEFAULT') !== 'DEFAULT' ? 'OVERRIDDEN (length: ' . strlen(apply_filters('pdfm_stripe_secret_key', 'DEFAULT')) . ')' : 'DEFAULT',
            'mu_plugin_loaded' => true,
        ];

        echo json_encode($debug_info, JSON_PRETTY_PRINT);
        exit;
    }
});
