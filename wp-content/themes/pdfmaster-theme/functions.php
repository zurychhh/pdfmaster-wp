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
 *
 * Uses Elementor API (Document save) to persist _elementor_data reliably.
 */
function pdfm_run_landing_migration_p0(): void {
    $post_id = (int) get_option('page_on_front');
    if ($post_id <= 0) { $post_id = 11; }

    $force = (bool) get_option('pdfm_force_migrate', false);
    $already = get_option('pdfm_migrated_landing_p0');
    if ($already && ! $force) {
        return;
    }

    error_log('PDFMaster: Migration P0 starting for post_id=' . $post_id);

    $existing_raw = get_post_meta($post_id, '_elementor_data', true);
    $existing_len = is_string($existing_raw) ? strlen($existing_raw) : 0;
    error_log('PDFMaster: Existing _elementor_data length: ' . $existing_len);

    if (! class_exists('Elementor\\Plugin')) {
        error_log('PDFMaster: Elementor not loaded, aborting migration');
        return;
    }
    $document = \Elementor\Plugin::$instance->documents->get($post_id);
    if (! $document) {
        error_log('PDFMaster: Elementor document not found for post_id=' . $post_id);
        return;
    }

    // Fetch current elements (plain array)
    $data = $document->get_elements_data();
    if (! is_array($data)) {
        error_log('PDFMaster: get_elements_data returned non-array');
        return;
    }

    $changed = false;

    // ---------- Helpers for content-based matching ----------
    $node_text_contains = function (array $node, string $needle): bool {
        $needle = strtolower($needle);
        $fields = ['title', 'title_text', 'editor', 'html', 'text', 'description', 'description_text'];
        if (isset($node['settings']) && is_array($node['settings'])) {
            foreach ($fields as $f) {
                if (!empty($node['settings'][$f]) && is_string($node['settings'][$f]) && strpos(strtolower((string)$node['settings'][$f]), $needle) !== false) {
                    return true;
                }
            }
            if (!empty($node['settings']['css_classes']) && is_string($node['settings']['css_classes']) && strpos(strtolower($node['settings']['css_classes']), $needle) !== false) {
                return true;
            }
        }
        return false;
    };

    $section_contains_texts = function (array $section, array $needles) use (&$section_contains_texts, $node_text_contains): bool {
        // BFS through elements
        $q = [$section];
        $found = array_fill_keys(array_map('strtolower', $needles), false);
        while ($q) {
            $n = array_shift($q);
            foreach ($needles as $s) {
                if ($node_text_contains($n, $s)) { $found[strtolower($s)] = true; }
            }
            if (!empty($n['elements']) && is_array($n['elements'])) {
                foreach ($n['elements'] as $child) { $q[] = $child; }
            }
        }
        foreach ($found as $ok) { if (!$ok) return false; }
        return true;
    };

    $section_has_iconbox_titles_like = function (array $section, int $minCount = 2): bool {
        $count = 0;
        $q = [$section];
        while ($q) {
            $n = array_shift($q);
            if (($n['elType'] ?? '') === 'widget' && ($n['widgetType'] ?? '') === 'icon-box') {
                $t = strtolower((string)($n['settings']['title_text'] ?? ''));
                if (strpos($t, 'pdf') !== false || preg_match('/compress|merge|split|convert/i', $t)) {
                    $count++;
                }
            }
            if (!empty($n['elements']) && is_array($n['elements'])) { foreach ($n['elements'] as $c) { $q[] = $c; } }
        }
        return $count >= $minCount;
    };

    $is_legacy_pricing_section = function (array $section): bool {
        if (($section['elType'] ?? '') !== 'section') return false;
        $q = [$section];
        while ($q) {
            $n = array_shift($q);
            if (($n['elType'] ?? '') === 'widget') {
                $wt = $n['widgetType'] ?? '';
                if ($wt === 'price-table') return true;
                $blob = strtolower(wp_json_encode($n['settings'] ?? []));
                if (strpos($blob, '3 credits') !== false || strpos($blob, 'credits') !== false) return true;
            }
            if (!empty($n['elements']) && is_array($n['elements'])) { foreach ($n['elements'] as $c) { $q[] = $c; } }
        }
        return false;
    };

    // ---------- 1) Remove legacy pricing sections (content-based) ----------
    $new_root = [];
    foreach ($data as $node) {
        if (is_array($node) && ($node['elType'] ?? '') === 'section' && $is_legacy_pricing_section($node)) {
            $changed = true;
            continue;
        }
        $new_root[] = $node;
    }
    $data = $new_root;

    // ---------- 2) Navbar: find section by texts and update ----------
    $navbar_index = -1;
    foreach ($data as $i => $node) {
        if (($node['elType'] ?? '') !== 'section') continue;
        if ($section_contains_texts($node, ['Pricing', 'All Tools'])) { $navbar_index = $i; break; }
    }
    if ($navbar_index >= 0) {
        // Add sticky class
        if (!isset($data[$navbar_index]['settings'])) $data[$navbar_index]['settings'] = [];
        $data[$navbar_index]['settings']['css_classes'] = trim(($data[$navbar_index]['settings']['css_classes'] ?? '') . ' navbar-sticky');

        // Replace any heading with "PDFSmart" (old brand) by icon-box "PDFMaster"
        $replace_pdfsmart = function (&$nodes) use (&$replace_pdfsmart): void {
            if (!is_array($nodes)) return;
            foreach ($nodes as $k => &$n) {
                if (!is_array($n)) continue;
                if (($n['elType'] ?? '') === 'widget') {
                    $settings = $n['settings'] ?? [];
                    $text = strtolower((string)($settings['title'] ?? $settings['title_text'] ?? $settings['editor'] ?? ''));
                    if (strpos($text, 'pdfsmart') !== false) {
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
                            ],
                            'elements' => [],
                        ];
                    }
                    // Special case: nav brand is icon-list with single item "PDFSmart"
                    if (($n['widgetType'] ?? '') === 'icon-list' && !empty($settings['icon_list']) && is_array($settings['icon_list'])) {
                        $one = $settings['icon_list'][0]['text'] ?? '';
                        if (is_string($one) && stripos($one, 'pdfsmart') !== false) {
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
                                ],
                                'elements' => [],
                            ];
                        }
                    }
                }
                if (isset($n['elements']) && is_array($n['elements'])) { $replace_pdfsmart($n['elements']); }
            }
        };
        if (isset($data[$navbar_index]['elements'])) { $replace_pdfsmart($data[$navbar_index]['elements']); }
        $changed = true;
    }

    // ---------- 3) Hero: find hero by class or headline, add badges ----------
    $hero_index = -1;
    foreach ($data as $i => $node) {
        if (($node['elType'] ?? '') !== 'section') continue;
        $has_class = !empty($node['settings']['css_classes']) && strpos(strtolower((string)$node['settings']['css_classes']), 'home-hero') !== false;
        $has_headline = $section_contains_texts($node, ['convert', 'merge']) || $section_contains_texts($node, ['pdf']);
        if ($has_class || $has_headline) { $hero_index = $i; break; }
    }
    if ($hero_index >= 0) {
        $items = [
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'No signup required', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Files deleted after 1 hour', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Bank-level encryption', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => '2M+ users monthly', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
        ];
        $badge_widget = [
            'id' => 'w_' . wp_generate_password(8, false, false),
            'elType' => 'widget',
            'isInner' => false,
            'widgetType' => 'icon-list',
            '_element_id' => 'pdfm_hero_badges',
            'settings' => [ 'icon_list' => $items, 'view' => 'inline', 'align' => 'center', 'css_classes' => 'home-hero', 'pdfm_marker' => 'hero_badges' ],
            'elements' => [],
        ];
        // Inject into first column's elements
        if (isset($data[$hero_index]['elements'][0]['elements']) && is_array($data[$hero_index]['elements'][0]['elements'])) {
            $data[$hero_index]['elements'][0]['elements'][] = $badge_widget;
            $changed = true;
        }
    }

    // ---------- 4) Tools: find tools section by multiple icon-boxes ----------
    $make_tools_header_section = function (): array {
        return [
            'id' => 'sec_' . wp_generate_password(8, false, false),
            'elType' => 'section',
            'isInner' => false,
            '_element_id' => 'pdfm_tools_hdr',
            'settings' => [ 'layout' => 'boxed', 'content_width' => 'boxed', 'pdfm_marker' => 'tools_header' ],
            'elements' => [
                [ 'id' => 'col_' . wp_generate_password(8, false, false), 'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 100 ], 'elements' => [
                    [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'heading', 'settings' => [ 'title' => 'All Tools, One Simple Price', 'header_size' => 'h2', 'align' => 'center' ], 'elements' => [] ],
                    [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => '$0.99 per action. No subscriptions, no packages, no complexity.', 'align' => 'center' ], 'elements' => [] ],
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

    $tools_index = -1;
    foreach ($data as $i => $node) {
        if (($node['elType'] ?? '') !== 'section') continue;
        if ($section_has_iconbox_titles_like($node, 2)) { $tools_index = $i; break; }
    }
    if ($tools_index >= 0) {
        // Insert header before tools section
        array_splice($data, $tools_index, 0, [$make_tools_header_section()]);
        $tools_index++;
        // Ensure 4 cards and add price/time to first two
        if (isset($data[$tools_index]['elements']) && is_array($data[$tools_index]['elements'])) {
            // Add price/time widgets to first two columns
            for ($i=0; $i<min(2, count($data[$tools_index]['elements'])); $i++) {
                if (isset($data[$tools_index]['elements'][$i]['elements']) && is_array($data[$tools_index]['elements'][$i]['elements'])) {
                    $data[$tools_index]['elements'][$i]['elements'][] = $make_price_block('$0.99', $i===0 ? '~8 seconds processing' : '~5 seconds processing');
                }
            }
            // Count columns and append missing tool cards
            $colCount = count($data[$tools_index]['elements']);
            if ($colCount < 4) {
                $split = $make_tool_card('fas fa-cut', 'Split PDF', 'Extract specific pages or split into separate files. Simple page range selection.', 'purple-bg');
                $split['elements'][] = $make_price_block('$0.99', '~6 seconds processing');
                $convert = $make_tool_card('fas fa-file-import', 'Convert to PDF', 'Convert Word, Excel, PowerPoint and images to PDF. Quality options available.', 'orange-bg');
                $convert['elements'][] = $make_price_block('$0.99', '~10 seconds processing');
                $data[$tools_index]['elements'][] = $split;
                $data[$tools_index]['elements'][] = $convert;
            }
            $changed = true;
        }
    }

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
            'id' => 'sec_' . wp_generate_password(8, false, false), 'elType' => 'section', 'isInner' => false,
            '_element_id' => 'pdfm_price_sec',
            'settings' => [ 'layout' => 'boxed', 'content_width' => 'boxed', 'gap' => 'wide', 'css_classes' => 'section-spacing', 'pdfm_marker' => 'pricing' ],
            'elements' => [
                [ 'id' => 'col_' . wp_generate_password(8, false, false), 'elType' => 'column', 'isInner' => false, 'settings' => [ '_column_size' => 100, 'horizontal_align' => 'center' ], 'elements' => [
                    [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'heading', 'settings' => [ 'title' => 'Simple, Honest Pricing', 'header_size' => 'h2', 'align' => 'center' ], 'elements' => [] ],
                    [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => false, 'widgetType' => 'text-editor', 'settings' => [ 'editor' => 'One price for everything. No tiers, no packages, no confusion.', 'align' => 'center' ], 'elements' => [] ],
                    // Card container as inner section with styled column
                    [ 'id' => 'sec_' . wp_generate_password(8, false, false), 'elType' => 'section', 'isInner' => true, 'settings' => [ 'layout' => 'boxed' ], 'elements' => [
                        [ 'id' => $card_col_id, 'elType' => 'column', 'isInner' => true, 'settings' => [ '_column_size' => 100, 'padding' => [ 'unit' => 'px', 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32 ], 'css_classes' => 'pricing-card' ], 'elements' => [
                            [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'html', 'settings' => [ 'html' => $price_html ], 'elements' => [] ],
                            [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'icon-list', 'settings' => [ 'icon_list' => $icon_items, 'align' => 'left' ], 'elements' => [] ],
                            [ 'id' => 'w_' . wp_generate_password(8, false, false), 'elType' => 'widget', 'isInner' => true, 'widgetType' => 'button', 'settings' => [ 'text' => 'Try Any Tool Now', 'size' => 'lg', 'button_type' => 'primary', 'button_css_id' => '', 'align' => 'center', 'button_full_width' => 'yes' ], 'elements' => [] ],
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

    // Insert pricing + comparison after tools section (by index)
    if ($tools_index >= 0) {
        array_splice($data, $tools_index + 1, 0, [$make_pricing_section(), $make_comparison_section()]);
        $changed = true;
    }

    if ($changed) {
        // Save via Elementor API (handles serialization & sanitization)
        try {
            if (method_exists($document, 'set_elements_data')) {
                $document->set_elements_data($data);
                $document->save([]);
            } else {
                $document->save([ 'elements' => $data ]);
            }
            // Fallback: force persist to post meta in case Document API no-ops under CLI
            update_post_meta($post_id, '_elementor_data', wp_slash(wp_json_encode($data)));
            // Clear caches
            if (class_exists('Elementor\\Plugin')) {
                \Elementor\Plugin::$instance->files_manager->clear_cache();
            }
            wp_cache_flush();

            update_option('pdfm_migrated_landing_p0', current_time('mysql'));
            if ($force) { delete_option('pdfm_force_migrate'); }

            error_log('PDFMaster: Data saved, verifying...');
            $saved = get_post_meta($post_id, '_elementor_data', true);
            error_log('PDFMaster: Saved _elementor_data length: ' . (is_string($saved) ? strlen($saved) : 0));
            error_log('PDFMaster: Migration P0 completed successfully');
        } catch (\Throwable $e) {
            error_log('PDFMaster: Migration save error: ' . $e->getMessage());
        }
    } else {
        error_log('PDFMaster: No changes detected; migration skipped');
    }
}

// Run on wp_loaded to ensure Elementor is fully initialized
add_action('wp_loaded', 'pdfm_run_landing_migration_p0');

/**
 * Phase 2 Migration: Convert hero shortcodes to native Elementor widgets
 * - Replace [pdfm_trust_badges] shortcode widgets with Icon List (inline)
 * - Replace [pdfm_hero_tools] shortcode widgets with a 3-column Icon Box row
 * Ensures Editor = Front parity and self-service editing.
 */
add_action('init', static function (): void {
    if (get_option('pdfm_migrated_phase2_hero') === 'yes') {
        return;
    }

    $home_id = (int) get_option('page_on_front');
    if ($home_id <= 0) {
        $home_id = 11; // fallback from environment
    }

    $raw = get_post_meta($home_id, '_elementor_data', true);
    if (! $raw) {
        return;
    }
    $json = is_string($raw) ? wp_unslash($raw) : $raw;
    $data = json_decode($json, true);
    if (! is_array($data)) {
        return;
    }

    $changed = false;

    $make_icon_list = function (): array {
        $items = [
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'No signup required', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ], 'icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Bank-level encryption', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ], 'icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => 'Files deleted after 1 hour', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ], 'icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
            [ '_id' => wp_generate_password(6, false, false), 'text' => '50,000+ docs processed weekly', 'selected_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ], 'icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ],
        ];
        return [
            'id' => 'pdfm_icon_list_' . wp_generate_password(6, false, false),
            'elType' => 'widget',
            'isInner' => false,
            'widgetType' => 'icon-list',
            'settings' => [
                'icon_list' => $items,
                'view' => 'inline',
                'align' => 'center',
                'css_classes' => 'pdfm-hero-badges',
            ],
            'elements' => [],
        ];
    };

    $make_icon_box = function (string $title, string $desc, string $icon): array {
        return [
            'id' => 'pdfm_icon_box_' . wp_generate_password(6, false, false),
            'elType' => 'widget',
            'isInner' => true,
            'widgetType' => 'icon-box',
            'settings' => [
                'title_text' => $title,
                'description_text' => $desc,
                'selected_icon' => [ 'value' => $icon, 'library' => 'fa-solid' ],
                'align' => 'center',
            ],
            'elements' => [],
        ];
    };

    $make_tools_section = function () use ($make_icon_box): array {
        $col = function (array $widget): array {
            return [
                'id' => 'pdfm_col_' . wp_generate_password(6, false, false),
                'elType' => 'column',
                'isInner' => false,
                'settings' => [ '_column_size' => 33.33 ],
                'elements' => [ $widget ],
            ];
        };
        $widgets = [
            $make_icon_box('Compress PDF', 'Reduce file size without losing quality.', 'fas fa-compress'),
            $make_icon_box('Merge PDF', 'Combine multiple PDFs into one.', 'fas fa-plus'),
            $make_icon_box('Convert PDF', 'Change format instantly.', 'fas fa-sync'),
        ];
        return [
            'id' => 'pdfm_tools_sec_' . wp_generate_password(6, false, false),
            'elType' => 'section',
            'isInner' => false,
            'settings' => [ 'layout' => 'columns', 'content_width' => 'boxed', 'css_classes' => 'pdfm-hero-tools' ],
            'elements' => [ $col($widgets[0]), $col($widgets[1]), $col($widgets[2]) ],
        ];
    };

    $convert = function (&$nodes) use (&$convert, $make_icon_list, $make_tools_section, &$changed) {
        if (! is_array($nodes)) return;
        foreach ($nodes as $i => &$node) {
            if (! is_array($node)) continue;
            // Recurse first
            if (isset($node['elements']) && is_array($node['elements'])) {
                $convert($node['elements']);
            }
            // Replace shortcodes
            if (isset($node['widgetType']) && $node['widgetType'] === 'shortcode' && isset($node['settings']['shortcode'])) {
                $short = trim((string) $node['settings']['shortcode']);
                if ($short === '[pdfm_trust_badges]') {
                    $nodes[$i] = $make_icon_list();
                    $changed = true;
                } elseif ($short === '[pdfm_hero_tools]') {
                    $nodes[$i] = $make_tools_section();
                    $changed = true;
                }
            }
        }
    };

    $convert($data);

    if ($changed) {
        update_post_meta($home_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        update_option('pdfm_migrated_phase2_hero', 'yes');
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

/**
 * Rebuild Section 1 (Hero) using CONTENT-BASED detection (no hardcoded IDs).
 * - Finds the hero section by text that contains "Convert, Merge & Edit PDFs" or by matching a hero-like heading.
 * - Updates: heading, subtitle, two CTA buttons.
 * - Leaves trust badges intact.
 */
function pdfm_rebuild_hero_by_content(): void {
    $post_id = (int) get_option('page_on_front');
    if ($post_id <= 0) { $post_id = 11; }

    if (! class_exists('Elementor\\Plugin')) { return; }
    $document = \Elementor\Plugin::$instance->documents->get($post_id);
    if (! $document) { return; }

    $data = $document->get_elements_data();
    if (! is_array($data)) { return; }

    $changed = false;

    $node_contains = function (array $node, string $needle): bool {
        $needle = strtolower($needle);
        $fields = ['title', 'title_text', 'editor', 'html', 'text', 'description', 'description_text'];
        $s = strtolower(wp_json_encode($node['settings'] ?? []));
        if ($s && strpos($s, $needle) !== false) return true;
        foreach ($fields as $f) {
            if (isset($node['settings'][$f]) && is_string($node['settings'][$f]) && strpos(strtolower((string)$node['settings'][$f]), $needle) !== false) {
                return true;
            }
        }
        return false;
    };

    $is_hero_section = function (array $section) use ($node_contains): bool {
        if (($section['elType'] ?? '') !== 'section') return false;
        // Match explicit phrase from legacy content
        if ($node_contains($section, 'convert, merge & edit pdfs')) return true;
        // Or a section containing any heading mentioning convert/merge/pdf near top
        $q = [$section]; $depth = 0; $found_heading = false;
        while ($q && $depth < 3) {
            $size = count($q);
            for ($i=0;$i<$size;$i++) {
                $n = array_shift($q);
                if (($n['elType'] ?? '') === 'widget' && ($n['widgetType'] ?? '') === 'heading') {
                    if ($node_contains($n, 'convert') || $node_contains($n, 'merge') || $node_contains($n, 'pdf')) {
                        $found_heading = true; break 2;
                    }
                }
                if (!empty($n['elements']) && is_array($n['elements'])) { foreach ($n['elements'] as $c) { $q[] = $c; } }
            }
            $depth++;
        }
        return $found_heading;
    };

    $update_hero = function (array &$section) use (&$changed) {
        // Find first heading, first text-editor, first two buttons and update texts
        $heading_done = false; $subtitle_done = false; $buttons_done = 0;
        $stack = [&$section];
        while ($stack) {
            $n =& $stack[array_key_last($stack)];
            array_pop($stack);
            if (!is_array($n)) continue;
            if (($n['elType'] ?? '') === 'widget') {
                $wt = $n['widgetType'] ?? '';
                if (!$heading_done && $wt === 'heading') {
                    $n['settings']['title'] = 'Professional PDF Tools in 30 Seconds';
                    $n['settings']['header_size'] = 'h1';
                    $n['settings']['align'] = 'center';
                    $heading_done = true; $changed = true;
                } elseif (!$subtitle_done && ($wt === 'text-editor' || $wt === 'heading')) {
                    $n['settings']['editor'] = 'Compress, merge, split and convert PDF files without installing software. Just $0.99 per action. No subscriptions, no hidden fees.';
                    $n['settings']['align'] = 'center';
                    $subtitle_done = true; $changed = true;
                } elseif ($wt === 'button' && $buttons_done < 2) {
                    $n['settings']['text'] = ($buttons_done === 0) ? 'Try Any Tool – $0.99' : 'See How It Works';
                    $buttons_done++; $changed = true;
                }
            }
            if (!empty($n['elements']) && is_array($n['elements'])) {
                // push children
                for ($i=count($n['elements'])-1; $i>=0; $i--) { $stack[] =& $n['elements'][$i]; }
            }
            if ($heading_done && $subtitle_done && $buttons_done >= 2) { break; }
        }
    };

    foreach ($data as &$node) {
        if ($is_hero_section($node)) {
            $update_hero($node);
            break;
        }
    }

    if ($changed) {
        try {
            if (method_exists($document, 'set_elements_data')) {
                $document->set_elements_data($data);
                $document->save([]);
            } else {
                $document->save([ 'elements' => $data ]);
            }
            update_post_meta($post_id, '_elementor_data', wp_slash(wp_json_encode($data)));
            if (class_exists('Elementor\\Plugin')) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
            wp_cache_flush();
        } catch (\Throwable $e) {
            // no-op
        }
    }
}

// WP-CLI command for Section 1 only
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('pdfm rebuild-hero', function () {
        pdfm_rebuild_hero_by_content();
        WP_CLI::success('Hero section rebuilt (content-based).');
    });
}

/**
 * Enqueue assets for Homepage P1 custom template
 */
if (! function_exists('pdfm_homepage_p1_assets')) {
    function pdfm_homepage_p1_assets(): void {
        if (is_page_template('page-homepage-p1.php')) {
            wp_enqueue_style(
                'pdfm-homepage-p1-css',
                get_template_directory_uri() . '/assets/css/homepage-p1.css',
                array(),
                '1.0.1'
            );

            wp_enqueue_script(
                'pdfm-homepage-p1-js',
                get_template_directory_uri() . '/assets/js/homepage-p1.js',
                array('jquery'),
                '1.0.1',
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'pdfm_homepage_p1_assets');

/**
 * Google Analytics 4 Tracking
 * Only loads on production environment
 */
if (! function_exists('pdfm_add_google_analytics')) {
    function pdfm_add_google_analytics(): void {
        // Only on production (Railway environment)
        if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'production') {
            ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-SG765F3EL7"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', 'G-SG765F3EL7');
            </script>
            <?php
        }
    }
}
add_action('wp_head', 'pdfm_add_google_analytics', 1);

