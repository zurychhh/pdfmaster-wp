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

        // Expose a consistent color palette to the block editor (reference to design tokens).
        add_theme_support('editor-color-palette', [
            [
                'name'  => __('Primary', 'pdfmaster-theme'),
                'slug'  => 'primary',
                'color' => '#2563EB', // Blue 600
            ],
            [
                'name'  => __('Secondary', 'pdfmaster-theme'),
                'slug'  => 'secondary',
                'color' => '#10B981', // Green 500
            ],
            [
                'name'  => __('Text', 'pdfmaster-theme'),
                'slug'  => 'text',
                'color' => '#1F2937', // Gray 800
            ],
            [
                'name'  => __('Accent', 'pdfmaster-theme'),
                'slug'  => 'accent',
                'color' => '#F59E0B', // Amber 500
            ],
            [
                'name'  => __('Background', 'pdfmaster-theme'),
                'slug'  => 'background',
                'color' => '#FFFFFF',
            ],
            [
                'name'  => __('Surface', 'pdfmaster-theme'),
                'slug'  => 'surface',
                'color' => '#F9FAFB', // Gray 50
            ],
        ]);
    }
}
add_action('after_setup_theme', 'pdfm_setup');

if (! function_exists('pdfm_enqueue_assets')) {
    /**
     * Enqueue the theme stylesheet for front-end rendering and define global CSS variables.
     */
    function pdfm_enqueue_assets(): void
    {
        // Google Fonts: Inter
        wp_enqueue_style(
            'pdfm-fonts-inter',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
            [],
            null
        );

        // Theme stylesheet
        wp_enqueue_style(
            'pdfmaster-theme-style',
            get_stylesheet_uri(),
            ['pdfm-fonts-inter'],
            (string) wp_get_theme()->get('Version')
        );

        // Global CSS variables and typography baseline for Elementor inheritance
        $global_css = <<<CSS
:root {
  /* Design tokens: colors */
  --pdfm-color-primary: #2563EB; /* Blue 600 */
  --pdfm-color-secondary: #10B981; /* Green 500 */
  --pdfm-color-text: #1F2937; /* Gray 800 */
  --pdfm-color-accent: #F59E0B; /* Amber 500 */
  --pdfm-color-background: #FFFFFF;
  --pdfm-color-surface: #F9FAFB; /* Gray 50 */

  /* Typography tokens */
  --pdfm-font-family-base: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Arial, sans-serif;
  --pdfm-font-size-body: 16px;
  --pdfm-line-height-body: 1.6;
  --pdfm-h1-size: 48px;
  --pdfm-h2-size: 36px;
  --pdfm-h1-size-mobile: 36px;
  --pdfm-h2-size-mobile: 28px;

  /* Spacing scale */
  --pdfm-space-0: 0;
  --pdfm-space-1: 4px;
  --pdfm-space-2: 8px;
  --pdfm-space-3: 12px;
  --pdfm-space-4: 16px;
  --pdfm-space-5: 20px;
  --pdfm-space-6: 24px;
  --pdfm-space-8: 32px;
  --pdfm-space-10: 40px;
  --pdfm-space-12: 48px;
  --pdfm-space-16: 64px;
  --pdfm-space-24: 96px;
}

/* Base application styles (Elementor will inherit when default schemes are disabled) */
body {
  font-family: var(--pdfm-font-family-base);
  font-size: var(--pdfm-font-size-body);
  line-height: var(--pdfm-line-height-body);
  color: var(--pdfm-color-text);
  background-color: var(--pdfm-color-background);
}

h1,
.elementor-heading-title.elementor-size-xxl,
.elementor-heading-title.elementor-size-xl {
  font-size: var(--pdfm-h1-size);
  line-height: 1.2;
}

h2,
.elementor-heading-title.elementor-size-large {
  font-size: var(--pdfm-h2-size);
  line-height: 1.25;
}

@media (max-width: 767px) {
  h1,
  .elementor-heading-title.elementor-size-xxl,
  .elementor-heading-title.elementor-size-xl {
    font-size: var(--pdfm-h1-size-mobile);
  }
  h2,
  .elementor-heading-title.elementor-size-large {
    font-size: var(--pdfm-h2-size-mobile);
  }
}

/* Convenience utility classes for spacing (optional) */
.u-mt-0 { margin-top: var(--pdfm-space-0) !important; }
.u-mt-1 { margin-top: var(--pdfm-space-1) !important; }
.u-mt-2 { margin-top: var(--pdfm-space-2) !important; }
.u-mt-3 { margin-top: var(--pdfm-space-3) !important; }
.u-mt-4 { margin-top: var(--pdfm-space-4) !important; }
.u-mt-5 { margin-top: var(--pdfm-space-5) !important; }
.u-mt-6 { margin-top: var(--pdfm-space-6) !important; }
.u-mt-8 { margin-top: var(--pdfm-space-8) !important; }
.u-mt-10 { margin-top: var(--pdfm-space-10) !important; }
.u-mt-12 { margin-top: var(--pdfm-space-12) !important; }
.u-mt-16 { margin-top: var(--pdfm-space-16) !important; }
.u-mt-24 { margin-top: var(--pdfm-space-24) !important; }

.u-pt-0 { padding-top: var(--pdfm-space-0) !important; }
.u-pt-1 { padding-top: var(--pdfm-space-1) !important; }
.u-pt-2 { padding-top: var(--pdfm-space-2) !important; }
.u-pt-3 { padding-top: var(--pdfm-space-3) !important; }
.u-pt-4 { padding-top: var(--pdfm-space-4) !important; }
.u-pt-5 { padding-top: var(--pdfm-space-5) !important; }
.u-pt-6 { padding-top: var(--pdfm-space-6) !important; }
.u-pt-8 { padding-top: var(--pdfm-space-8) !important; }
.u-pt-10 { padding-top: var(--pdfm-space-10) !important; }
.u-pt-12 { padding-top: var(--pdfm-space-12) !important; }
.u-pt-16 { padding-top: var(--pdfm-space-16) !important; }
.u-pt-24 { padding-top: var(--pdfm-space-24) !important; }
CSS;

        wp_add_inline_style('pdfmaster-theme-style', $global_css);

        // Enqueue homepage polish styles (only on front page)
        if ( is_front_page() ) {
            $polish = get_stylesheet_directory() . '/assets/css/home-polish.css';
            if ( file_exists( $polish ) ) {
                wp_enqueue_style( 'pdfmaster-home-polish', get_stylesheet_directory_uri() . '/assets/css/home-polish.css', [ 'pdfmaster-theme-style' ], (string) wp_get_theme()->get('Version') );
            }
        }
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

/**
 * Ensure Elementor inherits theme colors and typography and set Global (Kit) settings
 * to match the PDFMaster design system.
 */
add_action('elementor/loaded', static function (): void {
    // If Elementor isn't fully loaded, or kit manager not present, bail.
    if (! class_exists('Elementor\\Plugin')) {
        return;
    }

    // Disable Elementor default color and typography schemes so it inherits theme defaults
    // and uses Global Colors/Fonts we define below.
    if ('yes' !== get_option('elementor_disable_color_schemes')) {
        update_option('elementor_disable_color_schemes', 'yes');
    }
    if ('yes' !== get_option('elementor_disable_typography_schemes')) {
        update_option('elementor_disable_typography_schemes', 'yes');
    }

    try {
        $plugin = \Elementor\Plugin::instance();
        if (! isset($plugin->kits_manager)) {
            return;
        }
        $kit = $plugin->kits_manager->get_active_kit();
        if (! $kit) {
            return;
        }

        // Global Colors
        $kit->update_settings([
            'system_colors' => [
                [ '_id' => 'primary',   'title' => __('Primary', 'pdfmaster-theme'),   'color' => '#2563EB' ],
                [ '_id' => 'secondary', 'title' => __('Secondary', 'pdfmaster-theme'), 'color' => '#10B981' ],
                [ '_id' => 'text',      'title' => __('Text', 'pdfmaster-theme'),      'color' => '#1F2937' ],
                [ '_id' => 'accent',    'title' => __('Accent', 'pdfmaster-theme'),    'color' => '#F59E0B' ],
            ],
            'custom_colors' => [
                [ '_id' => 'background', 'title' => __('Background', 'pdfmaster-theme'), 'color' => '#FFFFFF' ],
                [ '_id' => 'surface',    'title' => __('Surface', 'pdfmaster-theme'),    'color' => '#F9FAFB' ],
            ],
        ]);

        // Global Typography (system)
        $kit->update_settings([
            'system_typography' => [
                [
                    '_id' => 'primary',
                    'title' => __('Primary', 'pdfmaster-theme'),
                    'typography_typography'   => 'custom',
                    'typography_font_family'  => 'Inter',
                    'typography_font_weight'  => '600',
                    'typography_line_height'  => [ 'unit' => 'em', 'size' => 1.2 ],
                    'typography_font_size'    => [ 'unit' => 'px', 'size' => 48 ],
                    'typography_font_size_tablet' => [ 'unit' => 'px', 'size' => 42 ],
                    'typography_font_size_mobile' => [ 'unit' => 'px', 'size' => 36 ],
                ],
                [
                    '_id' => 'secondary',
                    'title' => __('Secondary', 'pdfmaster-theme'),
                    'typography_typography'   => 'custom',
                    'typography_font_family'  => 'Inter',
                    'typography_font_weight'  => '600',
                    'typography_line_height'  => [ 'unit' => 'em', 'size' => 1.25 ],
                    'typography_font_size'    => [ 'unit' => 'px', 'size' => 36 ],
                    'typography_font_size_tablet' => [ 'unit' => 'px', 'size' => 32 ],
                    'typography_font_size_mobile' => [ 'unit' => 'px', 'size' => 28 ],
                ],
                [
                    '_id' => 'text',
                    'title' => __('Body', 'pdfmaster-theme'),
                    'typography_typography'  => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '400',
                    'typography_line_height' => [ 'unit' => 'em', 'size' => 1.6 ],
                    'typography_font_size'   => [ 'unit' => 'px', 'size' => 16 ],
                ],
                [
                    '_id' => 'accent',
                    'title' => __('Accent', 'pdfmaster-theme'),
                    'typography_typography'  => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '500',
                    'typography_line_height' => [ 'unit' => 'em', 'size' => 1.4 ],
                    'typography_font_size'   => [ 'unit' => 'px', 'size' => 16 ],
                ],
            ],
        ]);

    } catch (\Throwable $e) {
        // Silently ignore to avoid breaking front-end if Elementor structure changes.
        // error_log('PDFM Elementor kit configuration error: ' . $e->getMessage());
    }
});

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
