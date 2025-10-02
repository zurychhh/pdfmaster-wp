<?php
/**
 * Theme footer template.
 *
 * @package PDFMasterTheme
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

?>
</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <small>
            &copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>
        </small>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
