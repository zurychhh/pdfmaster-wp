(function ($) {
    'use strict';

    /**
     * Google Analytics 4 E-commerce Tracking Helper
     * Tracks payment funnel events for PDFMaster
     *
     * Event Map:
     * 1. begin_checkout - User clicks "Pay $1.99" button (modal opens)
     * 2. purchase - Payment successful (transaction complete)
     *
     * @param {string} eventName - GA4 event name
     * @param {object} params - Event parameters (value, currency, transaction_id, etc)
     */
    function trackEvent(eventName, params = {}) {
        if (typeof gtag === 'function') {
            gtag('event', eventName, params);
            console.log('GA4 E-commerce Event:', eventName, params); // Debug logging
        } else {
            console.log('GA4 not loaded - E-commerce event skipped:', eventName, params);
        }
    }

    const modalSelector = '.pdfm-payment-modal';
    let stripe, elements, cardElement;

    function openModal() {
        // GA4 Event: begin_checkout (e-commerce event)
        const toolName = $('input[name="operation"]:checked').val() || 'unknown';
        trackEvent('begin_checkout', {
            value: 1.99,
            currency: 'USD',
            tool_name: toolName,
            items: [{
                item_id: 'pdf_processing',
                item_name: 'PDF Processing (' + toolName + ')',
                price: 1.99,
                quantity: 1
            }]
        });

        $(modalSelector).fadeIn(200);
        ensureStripe();
    }

    function closeModal() {
        $(modalSelector).fadeOut(200);
    }

    $(document).on('click', '[data-pdfm-open-modal]', function (event) {
        event.preventDefault();
        openModal();
    });

    $(document).on('click', '[data-pdfm-close-modal]', function (event) {
        event.preventDefault();
        closeModal();
    });

    $(document).on('submit', '.pdfm-payment-form', async function (event) {
        event.preventDefault();

        try {
            ensureStripe();
            // 1) Create PaymentIntent
            const payload = new URLSearchParams();
            payload.append('action', 'pdfm_create_payment_intent');
            payload.append('nonce', pdfmPayments.nonce);
            const fileToken = $(modalSelector).attr('data-file-token') || '';
            if (!fileToken) throw new Error('Missing file token');
            payload.append('file_token', fileToken);
            const res = await fetch(pdfmPayments.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: payload.toString(),
            });
            const json = await res.json();
            if (!json.success) throw new Error(json.data && json.data.message || 'Failed to create PaymentIntent');

            const clientSecret = json.data.client_secret;

            // 2) Confirm card payment
            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                },
            });
            if (error) throw error;

            // 3) Confirm server side & update credits
            const confirmPayload = new URLSearchParams();
            confirmPayload.append('action', 'pdfm_confirm_payment');
            confirmPayload.append('nonce', pdfmPayments.nonce);
            confirmPayload.append('payment_intent_id', paymentIntent.id);
            confirmPayload.append('file_token', fileToken);
            const res2 = await fetch(pdfmPayments.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: confirmPayload.toString(),
            });
            const json2 = await res2.json();
            if (!json2.success) throw new Error(json2.data && json2.data.message || 'Payment confirm failed');

            // GA4 Event: purchase (e-commerce event)
            const toolName = $('input[name="operation"]:checked').val() || 'unknown';
            trackEvent('purchase', {
                transaction_id: fileToken,
                value: 1.99,
                currency: 'USD',
                tool_name: toolName,
                items: [{
                    item_id: 'pdf_processing',
                    item_name: 'PDF Processing (' + toolName + ')',
                    price: 1.99,
                    quantity: 1
                }]
            });

            // Success - trigger event immediately (no alert popup)
            closeModal();
            $(document).trigger('pdfm:payment:success', { file_token: fileToken });
        } catch (err) {
            console.error('Payment error', err);
            alert(err.message || 'Payment failed.');
        }
    });

    function ensureStripe() {
        if (stripe) return;
        stripe = Stripe(pdfmPayments.publishableKey);
        elements = stripe.elements();
        cardElement = elements.create('card');
        const mountEl = document.getElementById('pdfm-payment-element');
        if (mountEl && !mountEl.childElementCount) {
            cardElement.mount(mountEl);
        }
    }

    // Export ensureStripe globally for use by other scripts
    window.pdfmEnsureStripe = ensureStripe;
})(jQuery);
