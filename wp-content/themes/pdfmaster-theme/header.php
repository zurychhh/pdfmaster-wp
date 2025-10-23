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
            <svg class="logo-icon" width="24" height="24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
            <span><?php bloginfo('name'); ?></span>
        </a>
        <nav class="site-header__nav">
            <a href="<?php echo esc_url(home_url('/services/?tool=compress')); ?>" class="nav-link">Compress</a>
            <a href="<?php echo esc_url(home_url('/services/?tool=merge')); ?>" class="nav-link">Merge</a>
            <a href="<?php echo esc_url(home_url('/services/?tool=split')); ?>" class="nav-link">Split</a>
            <a href="<?php echo esc_url(home_url('/services/?tool=convert')); ?>" class="nav-link">Convert</a>
            <a href="<?php echo esc_url(home_url('/homepage-p1/#how-it-works')); ?>" class="nav-link">How It Works</a>
        </nav>
    </div>
</header>
<main id="primary" class="site-main">
