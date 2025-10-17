<div class="pdfm-payment-modal" style="display: none;">
    <div class="pdfm-payment-modal__dialog">
        <div class="pdfm-modal-header">
            <div class="pdfm-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <h2 class="pdfm-modal-title"><?php esc_html_e('Secure Payment', 'pdfmaster-payments'); ?></h2>
            <p class="pdfm-modal-subtitle"><?php esc_html_e('Complete your purchase to download your file', 'pdfmaster-payments'); ?></p>
        </div>

        <div class="pdfm-price-display">
            <div class="pdfm-price-amount">$0.99</div>
            <div class="pdfm-price-label"><?php esc_html_e('One-time payment', 'pdfmaster-payments'); ?></div>
        </div>

        <form class="pdfm-payment-form">
            <?php wp_nonce_field('pdfm_payments_nonce', '_pdfm_payments_form_nonce'); ?>

            <div class="pdfm-form-group">
                <label class="pdfm-form-label"><?php esc_html_e('Card Information', 'pdfmaster-payments'); ?></label>
                <div class="pdfm-stripe-element" id="pdfm-payment-element">
                    <!-- Stripe Elements placeholder -->
                </div>
            </div>

            <button type="submit" class="pdfm-btn-pay">
                <?php esc_html_e('Pay Now', 'pdfmaster-payments'); ?>
            </button>

            <a href="#" class="pdfm-btn-cancel" data-pdfm-close-modal>
                <?php esc_html_e('Cancel', 'pdfmaster-payments'); ?>
            </a>
        </form>

        <div class="pdfm-trust-badges">
            <div class="pdfm-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span><?php esc_html_e('Secure', 'pdfmaster-payments'); ?></span>
            </div>
            <div class="pdfm-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?php esc_html_e('Powered by Stripe', 'pdfmaster-payments'); ?></span>
            </div>
        </div>
    </div>
</div>
