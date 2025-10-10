(function ($) {
    'use strict';

    $(document).on('submit', '.pdfm-processor__form', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $container = $form.closest('.pdfm-processor');
        // Ensure result container exists
        let $result = $container.find('.pdfm-processor__result');
        if (!$result.length) {
            $result = $('<div class="pdfm-processor__result"></div>').appendTo($container);
        } else {
            $result.empty();
        }
        const formData = new FormData(this);
        formData.append('action', 'pdfm_process_pdf');
        formData.append('nonce', pdfmProcessor.nonce);

        $.ajax({
            url: pdfmProcessor.ajaxUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                // Show processing spinner & message quickly
                const processingHtml = [
                    '<div class="pdfm-processing">',
                    '  <div class="pdfm-spinner" aria-hidden="true"></div>',
                    '  <p class="pdfm-processing__text">Compressing your PDF... (usually 5-10 seconds)</p>',
                    '</div>'
                ].join('');
                $form.prop('disabled', true);
                $form.find('button, input, select').prop('disabled', true);
                $result.html(processingHtml);
            }
        })
            .done(function (response) {
                if (response && response.success && response.data) {
                    $form.hide();
                    const data = response.data;
                    // Build file stats (original -> compressed) with badge
                    var statsHtml = '';
                    if (data.original_size && data.compressed_size) {
                        var badge = '';
                        if (typeof data.reduction_percent === 'number') {
                            badge = '<div class="pdfm-stat-badge">' + data.reduction_percent + '% smaller</div>';
                        }
                        statsHtml = [
                            '<div class="pdfm-file-stats" aria-live="polite">',
                            '  <div class="pdfm-stat">',
                            '    <span class="pdfm-stat-label">Original:</span>',
                            '    <span class="pdfm-stat-value">' + data.original_size + '</span>',
                            '  </div>',
                            '  <div class="pdfm-stat-arrow">→</div>',
                            '  <div class="pdfm-stat">',
                            '    <span class="pdfm-stat-label">Compressed:</span>',
                            '    <span class="pdfm-stat-value">' + data.compressed_size + '</span>',
                            '  </div>',
                            badge,
                            '</div>'
                        ].join('');
                    }
                    // Always gate by payment now (pay-per-action $0.99)
                    const html = [
                        '<div class="pdfm-result">',
                        '  <p>Success! Your file is ready.</p>',
                        statsHtml,
                        '  <a class="button button-primary" href="#" data-pdfm-open-modal data-file-token="' + (data.token || data.download_token || '') + '">Pay $0.99 to Download</a>',
                        '  <button type="button" class="pdfm-reset button button-secondary">Process Another File</button>',
                        '</div>'
                    ].join('');
                    $result.html(html);

                    // Store file token on modal for payment flow
                    const $modal = $('.pdfm-payment-modal');
                    $modal.attr('data-file-token', data.token);

                    // After successful payment, enable the download link
                    $(document).one('pdfm:payment:success', function () {
                        // Clean UI: show only download + reset
                        const clean = [
                            '<div class="pdfm-result">',
                            '  <a class="pdfm-download button button-primary" href="' + data.downloadUrl + '" download>Download Your PDF</a>',
                            '  <button type="button" class="pdfm-reset button button-secondary">Process Another File</button>',
                            '</div>'
                        ].join('');
                        $result.html(clean);
                    });
                } else {
                    const message = (response && response.data && response.data.message) ? response.data.message : 'Compression failed. This might be due to a corrupted PDF. Please try another file.';
                    const errHtml = [
                        '<div class="pdfm-error" role="alert">',
                        '  <span class="pdfm-error__icon" aria-hidden="true">⚠️</span>',
                        '  <div class="pdfm-error__content">',
                        '    <p class="pdfm-error__message">' + message + '</p>',
                        '    <div class="pdfm-error__actions">',
                        '      <button type="button" class="pdfm-try-again button">Try Again</button>',
                        '    </div>',
                        '  </div>',
                        '</div>'
                    ].join('');
                    $result.html(errHtml);
                }
            })
            .fail(function (error) {
                console.error('PDF processing error', error);
                const message = (error && error.responseJSON && error.responseJSON.data && error.responseJSON.data.message)
                    ? error.responseJSON.data.message
                    : 'Compression failed. This might be due to a corrupted PDF. Please try another file.';
                const errHtml = [
                    '<div class="pdfm-error" role="alert">',
                    '  <span class="pdfm-error__icon" aria-hidden="true">⚠️</span>',
                    '  <div class="pdfm-error__content">',
                    '    <p class="pdfm-error__message">' + message + '</p>',
                    '    <div class="pdfm-error__actions">',
                    '      <button type="button" class="pdfm-try-again button">Try Again</button>',
                    '    </div>',
                    '  </div>',
                    '</div>'
                ].join('');
                $result.html(errHtml);
            });
    });

    // Reset handler to allow another processing run
    $(document).on('click', '.pdfm-reset', function () {
        // Full page reset for clean state
        window.location.reload();
    });

    // Try again handler
    $(document).on('click', '.pdfm-try-again', function () {
        const $container = $(this).closest('.pdfm-processor');
        const $form = $container.find('.pdfm-processor__form');
        $container.find('.pdfm-processor__result').empty();
        if ($form.length) {
            $form[0].reset();
            $form.show();
            $form.find('button, input, select').prop('disabled', false);
        }
    });
})(jQuery);
