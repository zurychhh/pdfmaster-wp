<?php
/**
 * Primary template file.
 *
 * @package PDFMasterTheme
 */

declare(strict_types=1);

get_header();
?>

<section class="elementor-canvas">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('This page is managed by Elementor templates.', 'pdfmaster-theme'); ?></p>
    <?php endif; ?>
</section>

<?php
get_footer();
