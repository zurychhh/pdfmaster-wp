(function ($) {
    'use strict';

    $(document).on('submit', '.pdfm-processor__form', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        formData.append('action', 'pdfm_process_pdf');
        formData.append('nonce', pdfmProcessor.nonce);

        $.ajax({
            url: pdfmProcessor.ajaxUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
            .done(function (response) {
                // TODO: Render success state using Elementor widgets.
                console.log('PDF processing response', response);
            })
            .fail(function (error) {
                console.error('PDF processing error', error);
            });
    });
})(jQuery);
