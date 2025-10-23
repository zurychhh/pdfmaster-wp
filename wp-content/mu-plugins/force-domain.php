<?php
/**
 * Plugin Name: Force PDFSpark Domain
 * Description: Prevents redirect loops by forcing pdfspark.app domain
 * Version: 1.0
 */

// Force domain on every request
add_filter('option_siteurl', function($url) {
    return 'https://pdfspark.app';
});

add_filter('option_home', function($url) {
    return 'https://pdfspark.app';
});

// Fix URLs in content
add_filter('content_url', function($url) {
    return str_replace('railway.app', 'pdfspark.app', $url);
});

add_filter('plugins_url', function($url) {
    return str_replace('railway.app', 'pdfspark.app', $url);
});

add_filter('site_url', function($url) {
    return str_replace('railway.app', 'pdfspark.app', $url);
}, 999);

add_filter('home_url', function($url) {
    return str_replace('railway.app', 'pdfspark.app', $url);
}, 999);

// Prevent WordPress from redirecting
remove_action('template_redirect', 'redirect_canonical');

// Force HTTPS
add_action('init', function() {
    if (!is_ssl() && !is_admin()) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit;
    }
}, 1);
