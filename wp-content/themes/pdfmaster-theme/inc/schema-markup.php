<?php
/**
 * Schema Markup - Structured Data (JSON-LD)
 *
 * Implements Organization, FAQ, WebSite, and WebPage schema
 * Part of SEO Foundation P0 implementation
 *
 * @package PDFMaster_Theme
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Organization schema in footer
 */
function pdfm_organization_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'PDFSpark',
        'url' => home_url('/'),
        'logo' => get_template_directory_uri() . '/assets/images/apple-touch-icon.png',
        'description' => 'Professional PDF tools for compression, merging, splitting, and conversion. Pay per use at $1.99 per action with no subscription required.',
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'email' => 'support@pdfspark.app',
            'url' => home_url('/')
        ),
        'sameAs' => array(
            // Add social media profiles when available
        )
    );

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

/**
 * Output WebSite schema
 */
function pdfm_website_schema() {
    // Only output on homepage
    if (!is_front_page()) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'PDFSpark',
        'url' => home_url('/'),
        'description' => 'Professional PDF tools: compress, merge, split & convert PDFs online',
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        )
    );

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

/**
 * Output WebPage schema
 */
function pdfm_webpage_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => wp_get_document_title(),
        'url' => get_permalink(),
        'description' => get_bloginfo('description'),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => 'PDFSpark'
        )
    );

    // Add breadcrumb if not homepage
    if (!is_front_page()) {
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => home_url('/')
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => get_the_title(),
                    'item' => get_permalink()
                )
            )
        );

        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

/**
 * Output FAQ schema for homepage
 */
function pdfm_faq_schema() {
    // Only output on homepage
    if (!is_front_page()) {
        return;
    }

    $faq_items = array(
        array(
            'question' => 'Why $1.99 per use instead of a subscription?',
            'answer' => 'Because most people process PDFs only 2-5 times per month—not 50. Why pay $10-20/month for something you barely use? With us, you pay $1.99 only when you need it. If you use it 10 times a year, that\'s $19.90 total instead of $120-240 for annual subscriptions.'
        ),
        array(
            'question' => 'Do I need to create an account?',
            'answer' => 'Nope. Just upload your file, pay, and download. We\'ll email you a receipt, but that\'s it. No passwords, no login, no profile.'
        ),
        array(
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major credit/debit cards via Stripe, plus PayPal and Google Pay.'
        ),
        array(
            'question' => 'Can I get a refund?',
            'answer' => 'Yes. If your file fails to process or the output is corrupted, email us within 24 hours for a full refund—no questions asked.'
        ),
        array(
            'question' => 'How long do you keep my files?',
            'answer' => 'Maximum 1 hour. After that, they\'re permanently deleted from our servers. We don\'t store, share, or access your documents.'
        ),
        array(
            'question' => 'Is there a file size limit?',
            'answer' => 'Each file can be up to 100MB. Need larger? Email us for custom enterprise pricing.'
        )
    );

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );

    foreach ($faq_items as $faq) {
        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

/**
 * Output Service schema for tools
 */
function pdfm_service_schema() {
    // Only output on services page
    if (!is_page(array('services', 'test-processor'))) {
        return;
    }

    $tool = isset($_GET['tool']) ? sanitize_text_field($_GET['tool']) : '';

    $services = array(
        'compress' => array(
            'name' => 'Compress Spark - PDF Compression Service',
            'description' => 'Compress PDF files by up to 90% without quality loss. Fast, secure, professional-grade PDF compression service.',
            'serviceType' => 'PDF Compression'
        ),
        'merge' => array(
            'name' => 'Merge Spark - PDF Merging Service',
            'description' => 'Merge multiple PDF files into one document in seconds. Professional PDF combining service.',
            'serviceType' => 'PDF Merging'
        ),
        'split' => array(
            'name' => 'Split Spark - PDF Splitting Service',
            'description' => 'Split PDF files into separate pages or extract specific pages. Fast and secure PDF splitting service.',
            'serviceType' => 'PDF Splitting'
        ),
        'convert' => array(
            'name' => 'Convert Spark - PDF Conversion Service',
            'description' => 'Convert images (JPG, PNG) to PDF instantly. Professional quality PDF conversion service.',
            'serviceType' => 'PDF Conversion'
        )
    );

    if ($tool && isset($services[$tool])) {
        $service = $services[$tool];

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service['name'],
            'description' => $service['description'],
            'serviceType' => $service['serviceType'],
            'provider' => array(
                '@type' => 'Organization',
                'name' => 'PDFSpark',
                'url' => home_url('/')
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => '1.99',
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
                'description' => 'Pay per use - no subscription required'
            ),
            'areaServed' => 'Worldwide',
            'availableChannel' => array(
                '@type' => 'ServiceChannel',
                'serviceUrl' => home_url('/services/?tool=' . $tool)
            )
        );

        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}

/**
 * Output SoftwareApplication schema
 */
function pdfm_software_application_schema() {
    // Only output on homepage or services page
    if (!is_front_page() && !is_page(array('services', 'test-processor'))) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => 'PDFSpark',
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'Any (Web-based)',
        'offers' => array(
            '@type' => 'Offer',
            'price' => '1.99',
            'priceCurrency' => 'USD',
            'description' => 'Pay per use - no subscription required'
        ),
        'aggregateRating' => array(
            '@type' => 'AggregateRating',
            'ratingValue' => '4.9',
            'ratingCount' => '2000000',
            'bestRating' => '5',
            'worstRating' => '1'
        ),
        'description' => 'Professional PDF tools for compression, merging, splitting, and conversion. Pay per use at $1.99 per action.',
        'url' => home_url('/')
    );

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

/**
 * Hook all schema functions to wp_footer
 */
function pdfm_output_all_schemas() {
    pdfm_organization_schema();
    pdfm_website_schema();
    pdfm_webpage_schema();
    pdfm_faq_schema();
    pdfm_service_schema();
    pdfm_software_application_schema();
}
add_action('wp_footer', 'pdfm_output_all_schemas', 99);
