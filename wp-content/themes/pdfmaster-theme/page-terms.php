<?php
/**
 * Template Name: Terms & Conditions
 * Template for displaying Terms & Conditions page with professional styling.
 *
 * @package PDFMasterTheme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<style>
/* Professional Legal Document Styling for Terms & Conditions */
.terms-container {
    max-width: 900px;
    margin: 60px auto;
    padding: 60px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
}

.terms-container h1 {
    font-size: 42px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 16px 0;
    line-height: 1.2;
}

.terms-last-updated {
    font-size: 14px;
    color: #666;
    margin: 0 0 60px 0;
    font-style: italic;
}

.terms-container h2 {
    font-size: 28px;
    font-weight: 600;
    color: #2563eb;
    margin: 60px 0 24px 0;
    line-height: 1.3;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 12px;
}

.terms-container h3 {
    font-size: 20px;
    font-weight: 600;
    color: #374151;
    margin: 36px 0 16px 0;
    line-height: 1.4;
}

.terms-container p {
    font-size: 16px;
    line-height: 1.8;
    color: #374151;
    margin: 0 0 20px 0;
}

.terms-container ul,
.terms-container ol {
    font-size: 16px;
    line-height: 1.8;
    color: #374151;
    margin: 0 0 20px 0;
    padding-left: 32px;
}

.terms-container li {
    margin-bottom: 12px;
}

.terms-container strong {
    font-weight: 600;
    color: #1a1a1a;
}

.terms-container a {
    color: #2563eb;
    text-decoration: underline;
}

.terms-container a:hover {
    color: #1d4ed8;
}

.terms-intro {
    font-size: 18px;
    color: #4b5563;
    margin-bottom: 48px;
    padding: 24px;
    background: #f9fafb;
    border-left: 4px solid #2563eb;
    border-radius: 4px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .terms-container {
        margin: 30px 20px;
        padding: 32px 24px;
    }

    .terms-container h1 {
        font-size: 32px;
        margin-bottom: 12px;
    }

    .terms-container h2 {
        font-size: 24px;
        margin: 48px 0 20px 0;
    }

    .terms-container h3 {
        font-size: 18px;
        margin: 28px 0 14px 0;
    }

    .terms-container p,
    .terms-container ul,
    .terms-container ol {
        font-size: 15px;
    }

    .terms-intro {
        font-size: 16px;
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .terms-container {
        margin: 20px 12px;
        padding: 24px 16px;
    }

    .terms-container h1 {
        font-size: 28px;
    }

    .terms-container h2 {
        font-size: 22px;
    }
}
</style>

<div class="terms-container">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php
get_footer();
