<?php
/**
 * Payment modal template.
 *
 * @var \PDFMaster\Payments\CreditsManager $credits
 * @var string $publishable_key
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$current_user_id = get_current_user_id();
$available_credits = $credits->get_user_credits($current_user_id);
?>

<div class="pdfm-payment-modal" data-publishable-key="<?php echo esc_attr($publishable_key); ?>" style="display: none;">
    <div class="pdfm-payment-modal__dialog">
        <header>
            <h2><?php esc_html_e('Top up your PDFMaster credits', 'pdfmaster-payments'); ?></h2>
            <p><?php esc_html_e('Pay-per-use bundle: $2.90 for 3 credits.', 'pdfmaster-payments'); ?></p>
            <p>
                <?php echo esc_html(sprintf(__('Available credits: %d', 'pdfmaster-payments'), $available_credits)); ?>
            </p>
        </header>

        <form class="pdfm-payment-form">
            <?php wp_nonce_field('pdfm_payments_nonce', '_pdfm_payments_form_nonce'); ?>
            <input type="hidden" name="credits" value="3" />
            <div id="pdfm-payment-element"><!-- Stripe Elements placeholder --></div>
            <div class="pdfm-payment-modal__actions">
                <button type="button" class="pdfm-payment-modal__button" data-pdfm-close-modal>
                    <?php esc_html_e('Cancel', 'pdfmaster-payments'); ?>
                </button>
                <button type="submit" class="pdfm-payment-modal__button pdfm-payment-modal__button--primary">
                    <?php esc_html_e('Purchase Credits', 'pdfmaster-payments'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
