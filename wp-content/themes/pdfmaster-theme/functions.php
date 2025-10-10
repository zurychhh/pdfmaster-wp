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

            // Note: We no longer enqueue auto-injection JS in hero to keep Editor = Front parity.
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

// Enqueue hero overrides CSS for front page and Elementor preview
add_action('wp_enqueue_scripts', static function (): void {
    $is_elementor_preview = isset($_GET['elementor-preview']);
    if (is_front_page() || $is_elementor_preview) {
        wp_enqueue_style(
            'pdfm-hero-overrides',
            get_stylesheet_directory_uri() . '/assets/css/hero-overrides.css',
            [ 'pdfmaster-theme-style' ],
            (string) wp_get_theme()->get('Version')
        );
    }
});

/**
 * One-time migration: insert Shortcode widgets ([pdfm_trust_badges], [pdfm_hero_tools])
 * into the Home page Elementor data after the secondary CTA. Ensures Editor = Front parity.
 */
add_action('init', static function (): void {
    // Run once
    if (get_option('pdfm_migrated_hero_shortcodes') === 'yes') {
        return;
    }

    $home_id = (int) get_option('page_on_front');
    if ($home_id <= 0) {
        $home_id = 11; // fallback from environment
    }

    $raw = get_post_meta($home_id, '_elementor_data', true);
    if (! $raw) {
        return; // No elementor data
    }

    $json = is_string($raw) ? wp_unslash($raw) : $raw;
    $data = json_decode($json, true);
    if (! is_array($data)) {
        return;
    }

    $inserted = false;

    $new_nodes = [
        [
            'id' => 'pdfm_trust_badges_' . wp_generate_password(6, false, false),
            'elType' => 'widget',
            'isInner' => false,
            'widgetType' => 'shortcode',
            'settings' => [ 'shortcode' => '[pdfm_trust_badges]' ],
            'elements' => [],
        ],
        [
            'id' => 'pdfm_hero_tools_' . wp_generate_password(6, false, false),
            'elType' => 'widget',
            'isInner' => false,
            'widgetType' => 'shortcode',
            'settings' => [ 'shortcode' => '[pdfm_hero_tools]' ],
            'elements' => [],
        ],
    ];

    $target_id = 'w_zeBvQh4E'; // "See All Tools & Pricing" button widget id observed in markup

    $insert_after = function (&$nodes) use (&$insert_after, $target_id, $new_nodes, &$inserted) {
        if (! is_array($nodes)) return;
        foreach ($nodes as $idx => &$node) {
            if (! is_array($node)) continue;
            if (isset($node['id']) && $node['id'] === $target_id) {
                array_splice($nodes, $idx + 1, 0, $new_nodes);
                $inserted = true;
                return;
            }
            if (isset($node['elements']) && is_array($node['elements'])) {
                $insert_after($node['elements']);
                if ($inserted) return;
            }
        }
    };

    $insert_after($data);

    if ($inserted) {
        update_post_meta($home_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        update_option('pdfm_migrated_hero_shortcodes', 'yes');
        // Optionally, regenerate Elementor CSS is left to manual tools for performance.
    }
});

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

/* ===== Landing Page Shortcodes (for Elementor HTML/Shortcode widgets) ===== */
// [pdfm_trust_badges]
add_shortcode('pdfm_trust_badges', static function (): string {
    return (
        '<div class="trust-badges">'
        . '<div class="trust-badge"><span class="icon">✓</span><span>No signup required</span></div>'
        . '<div class="trust-badge"><span class="icon">✓</span><span>Bank-level encryption</span></div>'
        . '<div class="trust-badge"><span class="icon">✓</span><span>Files deleted after 1 hour</span></div>'
        . '<div class="trust-badge"><span class="icon">✓</span><span>50,000+ documents processed weekly</span></div>'
        . '</div>'
    );
});

// [pdfm_pricing_table]
add_shortcode('pdfm_pricing_table', static function (): string {
    return <<<HTML
<div class="pricing-section">
  <h2>Simple, Honest Pricing – No Surprises</h2>
  <table class="pricing-table">
    <thead>
      <tr>
        <th>Tool</th>
        <th>Price</th>
        <th>Processing Time</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>PDF to Word</td>
        <td class="price">$0.99</td>
        <td>~8 seconds</td>
      </tr>
      <tr>
        <td>Merge PDFs (up to 10 files)</td>
        <td class="price">$0.99</td>
        <td>~5 seconds</td>
      </tr>
      <tr>
        <td>Compress PDF</td>
        <td class="price">$0.79</td>
        <td>~6 seconds</td>
      </tr>
      <tr>
        <td>PDF to Excel</td>
        <td class="price">$1.29</td>
        <td>~12 seconds</td>
      </tr>
      <tr class="bulk-row">
        <td>Bulk Discount (10 actions)</td>
        <td class="price-green">$7.99</td>
        <td class="savings">Save 20%</td>
      </tr>
    </tbody>
  </table>

  <div class="comparison-box">
    <h3>The Subscription Trap vs. The Smart Choice</h3>
    <div class="comparison-grid">
      <div class="comparison-bad">
        <span class="icon">❌</span>
        <p class="provider">Smallpdf Pro</p>
        <p class="cost">$108/year (9 uses = $12/month wasted)</p>
      </div>
      <div class="comparison-good">
        <span class="icon">✅</span>
        <p class="provider">Our Tool</p>
        <p class="cost">$8.91/year (9 uses at $0.99 each)</p>
      </div>
    </div>
    <p class="savings-text">You save $99.09 annually.</p>
  </div>
</div>
HTML;
});

// [pdfm_trust_section]
add_shortcode('pdfm_trust_section', static function (): string {
    return <<<HTML
<div class="trust-section">
  <h2>Your Privacy Is Non-Negotiable</h2>
  <div class="trust-grid">
    <div class="trust-item">
      <div class="trust-icon">💳</div>
      <h3>Secure Payments</h3>
      <p>We never see your card details. Payments processed by Stripe (PCI DSS Level 1 certified).</p>
    </div>
    <div class="trust-item">
      <div class="trust-icon">🛡️</div>
      <h3>Data Protection</h3>
      <p>256-bit AES encryption during transfer. Files auto-deleted after 60 minutes—no exceptions, no logs.</p>
    </div>
    <div class="trust-item">
      <div class="trust-icon">🔒</div>
      <h3>No Tracking, No Ads</h3>
      <p>We don't sell your data. We don't track your usage. You pay, you convert, you leave.</p>
    </div>
  </div>
  <div class="testimonial-card">
    <div class="stars">⭐⭐⭐⭐⭐</div>
    <p class="rating">4.8/5 from 2,300+ users on Trustpilot</p>
    <p class="quote">"Finally, a PDF tool that respects my wallet AND my privacy."</p>
    <p class="author">— Sarah T., Freelance Designer</p>
  </div>
</div>
HTML;
});

// [pdfm_hero_tools]
add_shortcode('pdfm_hero_tools', static function (): string {
    return <<<HTML
<div class="hero-tools-row">
  <div class="hero-tool">
    <div class="hero-tool-icon">🗜️</div>
    <h3>Compress PDF</h3>
    <p>Reduce file size without losing quality.</p>
  </div>
  <div class="hero-tool">
    <div class="hero-tool-icon">➕</div>
    <h3>Merge PDF</h3>
    <p>Combine multiple PDFs into one.</p>
  </div>
  <div class="hero-tool">
    <div class="hero-tool-icon">🔁</div>
    <h3>Convert PDF</h3>
    <p>Change format instantly.</p>
  </div>
</div>
HTML;
});
