        </header>

        <form class="pdfm-payment-form">
            <?php wp_nonce_field('pdfm_payments_nonce', '_pdfm_payments_form_nonce'); ?>
            <div id="pdfm-payment-element"><!-- Stripe Elements placeholder --></div>
            <div class="pdfm-payment-modal__actions">
                <button type="button" class="pdfm-payment-modal__button" data-pdfm-close-modal>
                    <?php esc_html_e('Cancel', 'pdfmaster-payments'); ?>
                </button>
                <button type="submit" class="pdfm-payment-modal__button pdfm-payment-modal__button--primary">
                    <?php esc_html_e('Pay $0.99 to Download', 'pdfmaster-payments'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
