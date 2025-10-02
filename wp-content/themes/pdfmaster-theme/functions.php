<?php
/**
 * Theme bootstrap file for PDFMaster Theme.
 *
 * @package PDFMasterTheme
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('pdfm_setup')) {
    /**
     * Register theme defaults and support for WordPress features.
     */
    function pdfm_setup(): void
    {
        load_theme_textdomain('pdfmaster-theme', get_template_directory() . '/languages');

        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            ]
        );

        // Elementor compatibility hooks.
        add_theme_support('elementor');
        add_theme_support('elementor-pro');
    }
}
add_action('after_setup_theme', 'pdfm_setup');

if (! function_exists('pdfm_enqueue_assets')) {
    /**
     * Enqueue the theme stylesheet for front-end rendering.
     */
    function pdfm_enqueue_assets(): void
    {
        wp_enqueue_style(
            'pdfmaster-theme-style',
            get_stylesheet_uri(),
            [],
            (string) wp_get_theme()->get('Version')
        );
    }
}
add_action('wp_enqueue_scripts', 'pdfm_enqueue_assets');

if (! function_exists('pdfm_register_elementor_locations')) {
    /**
     * Register default Elementor theme locations.
     */
    function pdfm_register_elementor_locations($elementor_theme_manager): void
    {
        if (is_object($elementor_theme_manager) && method_exists($elementor_theme_manager, 'register_location')) {
            $elementor_theme_manager->register_location('header');
            $elementor_theme_manager->register_location('footer');
            $elementor_theme_manager->register_location('single');
            $elementor_theme_manager->register_location('archive');
        }
    }
}
add_action('elementor/theme/register_locations', 'pdfm_register_elementor_locations');

if (! function_exists('pdfm_body_open_fallback')) {
    /**
     * Provide a fallback for wp_body_open() in older WordPress versions.
     */
    function pdfm_body_open_fallback(): void
    {
        if (! function_exists('wp_body_open')) {
            do_action('wp_body_open');
        }
    }
}
