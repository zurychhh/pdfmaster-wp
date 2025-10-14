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

// Load Elementor migration class
require_once get_template_directory() . '/includes/class-elementor-migration.php';

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
 * One-time Landing Page (ID: 11) migration for P0 fixes:
 * - Navbar: logo text to PDFMaster + sticky class
 * - Hero: add trust badges Icon List, remove extra Compress widget
 * - Tools: add header section, expand to 4 tools with price/time
 * - Pricing: remove legacy sections and add new $0.99 pricing + comparison
 */
add_action('init', static function (): void {
    if (get_option('pdfm_migrated_landing_p0') === 'yes') {
        return;
    }

    $home_id = (int) get_option('page_on_front');
    if ($home_id <= 0) {
        $home_id = 11;
    }
    $raw = get_post_meta($home_id, '_elementor_data', true);
    if (! $raw) return;
    $json = is_string($raw) ? wp_unslash($raw) : $raw;
    $data = json_decode($json, true);
    if (! is_array($data)) return;

    $changed = false;

    $remove_section_ids = ['5e69c9dd','6364e06e','3e39884c'];
    $tools_section_id = '4931b29f';
    $navbar_section_id = '66cea970';
    $hero_section_id = '5a46820c';
    $navbar_logo_widget_id = '481e5c9c';
    $hero_remove_widget_id = '105296c2';

    // Helper: recursively remove widget by id
    $remove_widget_by_id = function (&$nodes, string $id) use (&$remove_widget_by_id): void {
        if (! is_array($nodes)) return;
        foreach ($nodes as $i => &$n) {
            if (! is_array($n)) continue;
            if (isset($n['id']) && $n['id'] === $id && (isset($n['elType']) && $n['elType'] !== 'section')) {
                array_splice($nodes, $i, 1);
                return;
            }
            if (isset($n['elements']) && is_array($n['elements'])) {
                $remove_widget_by_id($n['elements'], $id);
            }
        }
    };

    // 1) Remove legacy pricing sections at root
    $new_root = [];
    foreach ($data as $node) {
        if (isset($node['id']) && in_array($node['id'], $remove_section_ids, true)) {
            $changed = true;
            continue;
        }
        $new_root[] = $node;
    }
    $data = $new_root;

    // 2) Navbar: set sticky class on section + replace logo widget
    $apply_navbar = function (&$nodes) use ($navbar_section_id, $navbar_logo_widget_id, &$changed) {
        foreach ($nodes as &$n) {
            if (! is_array($n)) continue;
            if (isset($n['id']) && $n['id'] === $navbar_section_id) {
                // Add sticky CSS class to section
                if (! isset($n['settings'])) $n['settings'] = [];
                $n['settings']['css_classes'] = trim(($n['settings']['css_classes'] ?? '') . ' navbar-sticky');
            }
            if (isset($n['id']) && $n['id'] === $navbar_logo_widget_id && ($n['elType'] ?? '') === 'widget') {
                // Replace with Icon Box inline
                $n = [
                    'id' => 'pdfm_nav_logo_' . wp_generate_password(6, false, false),
                    'elType' => 'widget',
                    'isInner' => false,
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'selected_icon' => [ 'value' => 'fas fa-file-pdf', 'library' => 'fa-solid' ],
                        'title_text' => 'PDFMaster',
                        'title_size' => 'span',
                        'view' => 'inline',
                        'align' => 'left',
                        'title_color' => '',
                    ],
                    'elements' => [],
                ];
                $changed = true;
            }
            if (isset($n['elements']) && is_array($n['elements'])) {
                $apply_navbar($n['elements']);
            }
        }
    };
    $apply_navbar($data);

    // 3) Hero: remove extra Compress widget and add trust badges Icon List to hero section
    $add_hero_badges = function (&$nodes) use ($hero_section_id, $hero_remove_widget_id, &$remove_widget_by_id, &$changed) {
        foreach ($nodes as &$n) {
            if (! is_array($n)) continue;
            if (isset($n['id']) && $n['id'] === $hero_section_id) {
                // remove the extra widget anywhere under this section
                if (isset($n['elements'])) $remove_widget_by_id($n['elements'], $hero_remove_widget_id);
                // append Icon List to the last column in this section
                $items = [
                    [ '_id' => wp_generate_password(6, false, false), 'text' => 'No signup required', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
                    [ '_id' => wp_generate_password(6, false, false), 'text' => 'Files deleted after 1 hour', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
                    [ '_id' => wp_generate_password(6, false, false), 'text' => 'Bank-level encryption', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
                    [ '_id' => wp_generate_password(6, false, false), 'text' => '2M+ users monthly', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
                ];
                $badge_widget = [
                    'id' => 'pdfm_hero_badges_' . wp_generate_password(6, false, false),
                    'elType' => 'widget',
                    'isInner' => false,
                    'widgetType' => 'icon-list',
                    'settings' => [ 'icon_list' => $items, 'view' => 'inline', 'align' => 'center', 'css_classes' => 'home-hero' ],
                    'elements' => [],
                ];
                // Place into first column if exists
                if (isset($n['elements'][0]['elements']) && is_array($n['elements'][0]['elements'])) {
                    $n['elements'][0]['elements'][] = $badge_widget;
                    $changed = true;
                }
            }
            if (isset($n['elements'])) $add_hero_badges($n['elements']);
        }
    };
    $add_hero_badges($data);

    // 4) Tools: add header section above, and ensure 4 cards with price/time
    $make_tools_header_section = function (): array {
        return [
            'id' => 'pdfm_tools_hdr_' . wp_generate_password(6, false, false),
            'elType' => 'section',
            'isInner' => false,
            'settings' => [ 'layout' => 'boxed', 'content_width' => 'boxed' ],
            'elements' => [
                [ 'id' => 'pdfm_tools_hdr_col_' . wp_generate_password(6, false, false), 'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 100 ], 'elements' => [
                    [ 'id' => 'pdfm_tools_hdr_h_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'heading', 'settings' => [ 'title' => 'All Tools, One Simple Price', 'header_size' => 'h2', 'align' => 'center' ], 'elements' => [] ],
                    [ 'id' => 'pdfm_tools_hdr_p_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => '$0.99 per action. No subscriptions, no packages, no complexity.', 'align' => 'center' ], 'elements' => [] ],
                ]],
            ],
        ];
    };

    $make_price_block = function (string $price, string $time): array {
        $html = '<div style="margin-top:16px;'."text-align:center;\">\n"
              . '<div style="font-size:32px;font-weight:700;color:#2563EB;">' . esc_html($price) . '</div>\n'
              . '<div style="font-size:14px;color:#6B7280;margin-top:4px;">' . esc_html($time) . '</div>\n'
              . '</div>';
        return [ 'id' => 'pdfm_price_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => $html ], 'elements' => [] ];
    };

    $make_tool_card = function (string $icon, string $title, string $desc, string $bgClass) use ($make_price_block): array {
        return [
            'id' => 'pdfm_tool_col_' . wp_generate_password(6, false, false),
            'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 50 ], 'elements' => [
                [ 'id' => 'pdfm_tool_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'icon-box', 'settings' => [
                    'selected_icon' => [ 'value' => $icon, 'library' => 'fa-solid' ],
                    'view' => 'stacked', 'align' => 'center', 'title_text' => $title, 'description_text' => $desc,
                    'css_classes' => 'tool-card',
                ], 'elements' => [] ],
            ],
        ];
    };

    // Insert tools header section before tools grid section
    $with_header = [];
    $inserted_header = false;
    foreach ($data as $node) {
        if (! $inserted_header && isset($node['id']) && $node['id'] === $tools_section_id) {
            $with_header[] = $make_tools_header_section();
            $inserted_header = true;
        }
        $with_header[] = $node;
    }
    if ($inserted_header) { $data = $with_header; $changed = true; }

    // Expand tools grid section to 4 cards and add price/time blocks to two existing columns
    $expand_tools = function (&$nodes) use ($tools_section_id, $make_tool_card, $make_price_block, &$changed) {
        foreach ($nodes as &$n) {
            if (! is_array($n)) continue;
            if (isset($n['id']) && $n['id'] === $tools_section_id && isset($n['elements']) && is_array($n['elements'])) {
                // Add price/time widget to first two columns if not present
                for ($i=0; $i<min(2, count($n['elements'])); $i++) {
                    if (isset($n['elements'][$i]['elements']) && is_array($n['elements'][$i]['elements'])) {
                        $n['elements'][$i]['elements'][] = $make_price_block('$0.99', $i===0 ? '~8 seconds processing' : '~5 seconds processing');
                    }
                }
                // Append 2 new tool columns
                $split = $make_tool_card('fas fa-cut', 'Split PDF', 'Extract specific pages or split into separate files. Simple page range selection.', 'purple-bg');
                $split['elements'][] = $make_price_block('$0.99', '~6 seconds processing');
                $convert = $make_tool_card('fas fa-file-import', 'Convert to PDF', 'Convert Word, Excel, PowerPoint and images to PDF. Quality options available.', 'orange-bg');
                $convert['elements'][] = $make_price_block('$0.99', '~10 seconds processing');
                $n['elements'][] = $split;
                $n['elements'][] = $convert;
                $changed = true;
            }
            if (isset($n['elements'])) $expand_tools($n['elements']);
        }
    };
    $expand_tools($data);

    // 5) Add new Pricing section and comparison after tools
    $make_pricing_section = function (): array {
        $card_col_id = 'pdfm_price_col_' . wp_generate_password(6, false, false);
        $price_html = '<div style="text-align:center;">\n  <div style="font-size:72px;font-weight:700;color:#1F2937;">$0.99</div>\n  <div style="font-size:20px;color:#6B7280;margin-top:8px;">per action</div>\n</div>';
        $icon_items = [
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Any tool: Compress, Merge, Split, Convert', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Files up to 100MB', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'No signup required', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Secure processing with auto-delete', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'No subscription, no recurring charges', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
        ];
        return [
            'id' => 'pdfm_price_sec_' . wp_generate_password(6, false, false), 'elType' => 'section', 'isInner' => false,
            'settings' => [ 'layout' => 'boxed', 'content_width' => 'boxed', 'gap' => 'wide', 'css_classes' => 'section-spacing' ],
            'elements' => [
                [ 'id' => 'pdfm_price_colwrap_' . wp_generate_password(6, false, false), 'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 100, 'horizontal_align' => 'center' ], 'elements' => [
                    [ 'id' => 'pdfm_price_h_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'heading', 'settings' => [ 'title' => 'Simple, Honest Pricing', 'header_size' => 'h2', 'align' => 'center' ], 'elements' => [] ],
                    [ 'id' => 'pdfm_price_lead_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => 'One price for everything. No tiers, no packages, no confusion.', 'align' => 'center' ], 'elements' => [] ],
                    // Card container as inner section with styled column
                    [ 'id' => 'pdfm_price_card_' . wp_generate_password(6, false, false), 'elType' => 'section', 'isInner' => true, 'settings' => [ 'layout' => 'boxed' ], 'elements' => [
                        [ 'id' => $card_col_id, 'elType' => 'column', 'isInner' => true, 'settings' => [ '_column_size' => 100, 'padding' => [ 'unit' => 'px', 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32 ], 'css_classes' => 'pricing-card' ], 'elements' => [
                            [ 'id' => 'pdfm_price_html_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'html', 'settings' => [ 'html' => $price_html ], 'elements' => [] ],
                            [ 'id' => 'pdfm_price_bullets_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'icon-list', 'settings' => [ 'icon_list' => $icon_items, 'align' => 'left' ], 'elements' => [] ],
                            [ 'id' => 'pdfm_price_cta_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'button', 'settings' => [ 'text' => 'Try Any Tool Now', 'size' => 'lg', 'button_type' => 'primary', 'button_css_id' => '', 'align' => 'center', 'button_full_width' => 'yes' ], 'elements' => [] ],
                        ]],
                    ]],
                ]],
            ],
        ];
    };

    $make_comparison_section = function (): array {
        return [
            'id' => 'pdfm_comp_sec_' . wp_generate_password(6, false, false), 'elType' => 'section', 'isInner' => false,
            'settings' => [ 'layout' => 'boxed', 'content_width' => 'boxed', 'css_classes' => 'comparison-section' ],
            'elements' => [
                [ 'id' => 'pdfm_comp_col_' . wp_generate_password(6, false, false), 'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 100 ], 'elements' => [
                    [ 'id' => 'pdfm_comp_h_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'heading', 'settings' => [ 'title' => 'The Subscription Trap vs. The Smart Choice', 'header_size' => 'h3', 'align' => 'center' ], 'elements' => [] ],
                    [ 'id' => 'pdfm_comp_inner_' . wp_generate_password(6, false, false), 'elType' => 'section', 'isInner' => true, 'settings' => [ 'layout' => 'columns' ], 'elements' => [
                        [ 'id' => 'pdfm_comp_c1_' . wp_generate_password(6, false, false), 'elType' => 'column', 'isInner' => true, 'settings' => [ '_column_size' => 50, 'padding' => [ 'unit' => 'px', 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24 ] ], 'elements' => [
                            [ 'id' => 'pdfm_comp_bad_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'icon-box', 'settings' => [ 'selected_icon' => [ 'value' => 'fas fa-times-circle', 'library' => 'fa-solid' ], 'title_text' => 'Typical Competitor (Smallpdf Pro)', 'description_text' => "$108/year subscription\nMost users need only 9 actions/year\n= $99.09 wasted annually" ], 'elements' => [] ],
                        ]],
                        [ 'id' => 'pdfm_comp_c2_' . wp_generate_password(6, false, false), 'elType' => 'column', 'isInner' => true, 'settings' => [ '_column_size' => 50, 'padding' => [ 'unit' => 'px', 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24 ], 'css_classes' => 'good' ], 'elements' => [
                            [ 'id' => 'pdfm_comp_good_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'icon-box', 'settings' => [ 'selected_icon' => [ 'value' => 'fas fa-check-circle', 'library' => 'fa-solid' ], 'title_text' => 'PDFMaster', 'description_text' => "Pay only when you use it\n9 actions × $0.99 each\n= $8.91 total per year" ], 'elements' => [] ],
                        ]],
                    ]],
                    [ 'id' => 'pdfm_comp_note_' . wp_generate_password(6, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => '<div style="text-align:center;font-size:20px;font-weight:700;color:#1E3A8A;">Save $99.09 annually by paying only for what you actually use.</div>' ], 'elements' => [] ],
                ]],
            ],
        ];
    };

    // Insert pricing + comparison after tools section
    $with_pricing = [];
    $inserted_pricing = false;
    foreach ($data as $node) {
        $with_pricing[] = $node;
        if (! $inserted_pricing && isset($node['id']) && $node['id'] === $tools_section_id) {
            $with_pricing[] = $make_pricing_section();
            $with_pricing[] = $make_comparison_section();
            $inserted_pricing = true;
        }
    }
    if ($inserted_pricing) { $data = $with_pricing; $changed = true; }

    if ($changed) {
        update_post_meta($home_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        update_option('pdfm_migrated_landing_p0', 'yes');
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
