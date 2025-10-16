(function ($) {
    'use strict';

    // File management
    let selectedFiles = [];

    // Update file list display
    function updateFileList() {
        const $list = $('.pdfm-file-list');

        if (selectedFiles.length === 0) {
            $list.html('<p class="pdfm-no-files">No files added yet</p>');
            return;
        }

        let html = '<ul class="pdfm-files">';
        selectedFiles.forEach((file, index) => {
            const sizeKB = (file.size / 1024).toFixed(0);
            html += '<li class="pdfm-file-item">';
            html += '  <span class="pdfm-file-name">' + file.name + '</span>';
            html += '  <span class="pdfm-file-size">(' + sizeKB + ' KB)</span>';
            html += '  <button type="button" class="pdfm-remove-file" data-index="' + index + '">×</button>';
            html += '</li>';
        });
        html += '</ul>';

        $list.html(html);
    }

    // Initialize empty state
    $(document).ready(function () {
        updateFileList();
    });

    // Trigger file input when button clicked
    $(document).on('click', '.pdfm-add-file', function () {
        $('#pdfm-file-input').click();
    });

    // Handle file selection
    $(document).on('change', '#pdfm-file-input', function () {
        const file = this.files[0];
        if (!file) return;

        // Validate PDF
        if (file.type !== 'application/pdf') {
            alert('Only PDF files are supported.');
            this.value = '';
            return;
        }

        // Check size (100MB)
        const maxSize = 100 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File too large. Maximum size: 100MB');
            this.value = '';
            return;
        }

        // Check duplicate
        const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (exists) {
            alert('This file is already added.');
            this.value = '';
            return;
        }

        // Add to array
        selectedFiles.push(file);

        // Update UI
        updateFileList();

        // Reset input
        this.value = '';
    });

    // Remove file handler
    $(document).on('click', '.pdfm-remove-file', function () {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);
        updateFileList();
    });

    // Tool selector toggle
    $(document).on('change', 'input[name="operation"]', function () {
        const operation = $(this).val();

        // Toggle active state
        $('.pdfm-tool-option').removeClass('active');
        $(this).closest('.pdfm-tool-option').addClass('active');

        // Show/hide compression level
        $('.pdfm-level-group').toggle(operation === 'compress');

        // Toggle help text
        $('.pdfm-help[data-for]').hide();
        $('.pdfm-help[data-for="' + operation + '"]').show();
    });

    $(document).on('submit', '.pdfm-processor__form', function (event) {
        event.preventDefault();

        // Validate files added
        if (selectedFiles.length === 0) {
            alert('Please add at least one PDF file.');
            return;
        }

        const $form = $(this);
        const $container = $form.closest('.pdfm-processor');
        // Ensure result container exists
        let $result = $container.find('.pdfm-processor__result');
        if (!$result.length) {
            $result = $('<div class="pdfm-processor__result"></div>').appendTo($container);
        } else {
            $result.empty();
        }

        const operation = $('input[name="operation"]:checked').val();
        const compression_level = $('select[name="compression_level"]').val();

        // Build FormData with selected files
        const formData = new FormData();
        formData.append('action', 'pdfm_process_pdf');
        formData.append('nonce', pdfmProcessor.nonce);
        formData.append('operation', operation);
        formData.append('compression_level', compression_level);

        // Add all selected files
        selectedFiles.forEach((file, index) => {
            formData.append('pdf_files[]', file);
        });

        $.ajax({
            url: pdfmProcessor.ajaxUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                // Show processing spinner & message quickly
                const operation = formData.get('operation');
                const spinnerText = operation === 'merge'
                    ? 'Merging your PDFs... (usually 5-10 seconds)'
                    : 'Compressing your PDF... (usually 5-10 seconds)';

                const processingHtml = [
                    '<div class="pdfm-processing">',
                    '  <div class="pdfm-spinner" aria-hidden="true"></div>',
                    '  <p class="pdfm-processing__text">' + spinnerText + '</p>',
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
                    const operation = formData.get('operation');

                    // Build file stats (conditional by operation)
                    var statsHtml = '';
                    if (operation === 'compress' && data.original_size && data.compressed_size) {
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
                    } else if (operation === 'merge') {
                        statsHtml = '<p class="pdfm-merge-success">✓ Successfully merged ' + selectedFiles.length + ' files</p>';
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
        selectedFiles = [];
        updateFileList();
        // Full page reset for clean state
        window.location.reload();
    });

    // Try again handler
    $(document).on('click', '.pdfm-try-again', function () {
        selectedFiles = [];
        updateFileList();
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
