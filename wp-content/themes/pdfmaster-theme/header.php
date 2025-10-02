<?php
/**
 * Theme header template.
 *
 * @package PDFMasterTheme
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
pdfm_body_open_fallback();
if (function_exists('wp_body_open')) {
    wp_body_open();
}
?>
<header class="site-header">
    <div class="site-header__inner">
        <a class="site-header__branding" href="<?php echo esc_url(home_url('/')); ?>">
            <?php bloginfo('name'); ?>
        </a>
    </div>
</header>
<main id="primary" class="site-main">
