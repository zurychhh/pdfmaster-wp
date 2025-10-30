<?php
/**
 * SEO Metadata - Title Tags, Meta Descriptions, Open Graph, Twitter Cards
 *
 * Implements P0 critical SEO elements for PDFSpark
 * Part of SEO Foundation implementation
 *
 * @package PDFMaster_Theme
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize document title
 */
function pdfm_custom_document_title($title_parts) {
    $site_name = 'PDFSpark';
    $separator = '|';

    // Homepage (static front page OR blog index)
    if (is_front_page() || is_home()) {
        $title_parts['title'] = 'Professional PDF Tools in 30 Seconds';
        $title_parts['tagline'] = 'PDFSpark - $1.99 Per Action';
        return $title_parts;
    }

    // Services page (check if page slug is 'services' or 'test-processor')
    if (is_page(array('services', 'test-processor'))) {
        $tool = isset($_GET['tool']) ? sanitize_text_field($_GET['tool']) : '';

        if ($tool) {
            $tool_titles = array(
                'compress' => 'Compress Spark - Reduce PDF Size by 90%',
                'merge' => 'Merge Spark - Combine Multiple PDFs',
                'split' => 'Split Spark - Extract Pages from PDF',
                'convert' => 'Convert Spark - Images to PDF Conversion'
            );

            if (isset($tool_titles[$tool])) {
                $title_parts['title'] = $tool_titles[$tool];
                $title_parts['tagline'] = 'PDFSpark - Fast PDF Processing';
                return $title_parts;
            }
        }

        $title_parts['title'] = 'PDF Tools';
        $title_parts['tagline'] = 'PDFSpark - Professional PDF Processing';
        return $title_parts;
    }

    // Terms & Conditions page
    if (is_page('terms')) {
        $title_parts['title'] = 'Terms and Conditions';
        $title_parts['tagline'] = 'PDFSpark';
        return $title_parts;
    }

    // Default pages - keep WordPress default but ensure PDFSpark branding
    if (isset($title_parts['site'])) {
        $title_parts['site'] = $site_name;
    }

    return $title_parts;
}
add_filter('document_title_parts', 'pdfm_custom_document_title');

/**
 * Add meta descriptions and other meta tags
 */
function pdfm_add_meta_tags() {
    $description = '';
    $keywords = '';
    $og_type = 'website';
    $og_image = get_template_directory_uri() . '/assets/images/pdfspark-og-image.jpg'; // TODO: Create OG image
    $canonical_url = '';

    // Homepage (static front page OR blog index)
    if (is_front_page() || is_home()) {
        $description = 'Compress, merge, split & convert PDF files without software. Only $1.99 per action, no subscription. Bank-level encryption, files deleted after 1 hour. Try now!';
        $keywords = 'pdf compressor, merge pdf, split pdf, convert to pdf, pdf tools, compress pdf online';
        $canonical_url = home_url('/');
    }

    // Services page
    elseif (is_page(array('services', 'test-processor'))) {
        $tool = isset($_GET['tool']) ? sanitize_text_field($_GET['tool']) : '';

        $tool_descriptions = array(
            'compress' => 'Compress PDF files by up to 90% without quality loss. Fast, secure, only $1.99. No subscription, no software install. Files auto-deleted after 1 hour.',
            'merge' => 'Merge multiple PDF files into one document in seconds. Secure processing, only $1.99. No subscription required. Files auto-deleted after 1 hour.',
            'split' => 'Split PDF files into separate pages or extract specific pages. Fast & secure, only $1.99. No subscription. Files deleted after 1 hour.',
            'convert' => 'Convert images (JPG, PNG) to PDF instantly. Professional quality, only $1.99. No subscription. Secure processing, files auto-deleted after 1 hour.'
        );

        $tool_keywords = array(
            'compress' => 'compress pdf, reduce pdf size, pdf compressor online, shrink pdf, pdf compression',
            'merge' => 'merge pdf, combine pdf, join pdf files, pdf merger online, merge multiple pdf',
            'split' => 'split pdf, extract pdf pages, pdf splitter, divide pdf, separate pdf pages',
            'convert' => 'convert to pdf, jpg to pdf, png to pdf, image to pdf, pdf converter'
        );

        if ($tool && isset($tool_descriptions[$tool])) {
            $description = $tool_descriptions[$tool];
            $keywords = $tool_keywords[$tool];
        } else {
            $description = 'Professional PDF tools: compress, merge, split, and convert PDF files. Only $1.99 per action. No subscription. Bank-level encryption.';
            $keywords = 'pdf tools, pdf processing, compress pdf, merge pdf, split pdf, convert pdf';
        }

        $canonical_url = home_url('/services/');
        if ($tool) {
            $canonical_url = add_query_arg('tool', $tool, $canonical_url);
        }
    }

    // Terms & Conditions page
    elseif (is_page('terms')) {
        $description = 'Terms and Conditions for using PDFSpark PDF processing services. Read our service agreement, privacy policy, and usage guidelines.';
        $keywords = 'terms and conditions, service agreement, privacy policy, pdfspark terms';
        $canonical_url = home_url('/terms/');
    }

    // Default for other pages
    else {
        $description = get_bloginfo('description');
        $canonical_url = get_permalink();
    }

    // Output meta description
    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    // Output meta keywords (optional, not used by Google but some search engines still use it)
    if ($keywords) {
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
    }

    // Canonical URL
    if ($canonical_url) {
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
    }

    // Open Graph tags
    $og_title = wp_get_document_title();
    $og_url = $canonical_url ? $canonical_url : get_permalink();

    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:site_name" content="PDFSpark">' . "\n";

    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
    }

    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
    }

    // Additional meta tags
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
}
add_action('wp_head', 'pdfm_add_meta_tags', 1);
