(function ($) {
    'use strict';

    const modalSelector = '.pdfm-payment-modal';
    let stripe, elements, cardElement;

    function openModal() {
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
            payload.append('credits', '3');
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
            const res2 = await fetch(pdfmPayments.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: confirmPayload.toString(),
            });
            const json2 = await res2.json();
            if (!json2.success) throw new Error(json2.data && json2.data.message || 'Payment confirm failed');

            alert('Payment successful. Your credits have been added.');
            closeModal();
            $(document).trigger('pdfm:payment:success', { balance: json2.data.balance });
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
})(jQuery);
