(function ($) {
    'use strict';

    $(document).on('submit', '.pdfm-processor__form', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $container = $form.closest('.pdfm-processor');
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
                if (response && response.success && response.data && response.data.url) {
                    const url = response.data.url;
                    // Hide form and show download UI
                    $form.hide();
                    const resultHtml = [
                        '<div class="pdfm-result">',
                        '<p>Success! Your file is ready.</p>',
                        '<a class="pdfm-download button button-primary" href="' + url + '" download>Download Your Compressed PDF</a> ',
                        '<button type="button" class="pdfm-reset button button-secondary">Process Another File</button>',
                        '</div>'
                    ].join('');
                    $container.append(resultHtml);
                } else {
                    const message = (response && response.data && response.data.message) ? response.data.message : 'Processing failed.';
                    alert(message);
                }
            })
            .fail(function (error) {
                console.error('PDF processing error', error);
                alert('An error occurred while processing the PDF.');
            });
    });

    // Reset handler to allow another processing run
    $(document).on('click', '.pdfm-reset', function () {
        const $container = $(this).closest('.pdfm-processor');
        const $form = $container.find('.pdfm-processor__form');
        $container.find('.pdfm-result').remove();
        if ($form.length) {
            $form[0].reset();
            $form.show();
        }
    });
})(jQuery);
