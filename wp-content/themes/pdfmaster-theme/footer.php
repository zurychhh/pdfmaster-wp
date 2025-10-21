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
        <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/test-processor/?tool=compress')); ?>" class="footer-link">Compress PDF</a>
            <a href="<?php echo esc_url(home_url('/test-processor/?tool=merge')); ?>" class="footer-link">Merge PDFs</a>
            <a href="<?php echo esc_url(home_url('/test-processor/?tool=split')); ?>" class="footer-link">Split PDF</a>
            <a href="<?php echo esc_url(home_url('/test-processor/?tool=convert')); ?>" class="footer-link">Convert to PDF</a>
        </div>
        <div class="footer-contact">
            <a href="mailto:contact@oleksiakconsulting.com" class="footer-email">contact@oleksiakconsulting.com</a>
        </div>
        <div class="footer-legal">
            <a href="#" class="footer-legal-link">Terms & Conditions</a>
            <span class="footer-separator">•</span>
            <small>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></small>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
