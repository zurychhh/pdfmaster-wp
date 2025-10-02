(function ($) {
    'use strict';

    const modalSelector = '.pdfm-payment-modal';

    function openModal() {
        $(modalSelector).fadeIn(200);
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

    $(document).on('submit', '.pdfm-payment-form', function (event) {
        event.preventDefault();

        const formData = $(this).serializeArray();
        const payload = new URLSearchParams(formData.map(({ name, value }) => [name, value]));
        payload.append('action', 'pdfm_initiate_payment');
        payload.append('nonce', pdfmPayments.nonce);

        fetch(pdfmPayments.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: payload.toString(),
        })
            .then((response) => response.json())
            .then((data) => {
                // TODO: Handle Stripe payment intent client confirmation.
                console.log('Payment response', data);
            })
            .catch((error) => {
                console.error('Payment error', error);
            });
    });
})(jQuery);
