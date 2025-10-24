(function ($) {
    'use strict';

    /**
     * Google Analytics 4 Event Tracking Helper
     * Tracks conversion funnel events for PDFMaster
     *
     * Event Map:
     * 1. tool_selected - User clicks tool tab (compress/merge/split/convert)
     * 2. file_uploaded - File successfully uploaded and validated
     * 3. processing_started - User clicks process button (Compress/Merge/etc)
     * 4. processing_complete - Processing successful, results shown
     * 5. file_downloaded - User clicks download button
     *
     * @param {string} eventName - GA4 event name
     * @param {object} params - Event parameters (tool_name, file_size_kb, etc)
     */
    function trackEvent(eventName, params = {}) {
        if (typeof gtag === 'function') {
            gtag('event', eventName, params);
            console.log('GA4 Event:', eventName, params); // Debug logging
        } else {
            console.log('GA4 not loaded - Event skipped:', eventName, params);
        }
    }

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

        // ✨ OPTIMIZATION: Read ?tool= parameter from URL and activate corresponding tab
        const urlParams = new URLSearchParams(window.location.search);
        const toolParam = urlParams.get('tool');
        const validTools = ['compress', 'merge', 'split', 'convert'];

        if (toolParam && validTools.includes(toolParam)) {
            // Find and click the tab for this tool
            const $targetRadio = $('input[name="operation"][value="' + toolParam + '"]');
            if ($targetRadio.length) {
                $targetRadio.prop('checked', true).trigger('change');
                $targetRadio.closest('.pdfm-tab').addClass('active');

                // Show/hide conditional fields based on tool
                $('.pdfm-level-group').toggle(toolParam === 'compress');
                $('.pdfm-pages-group').toggle(toolParam === 'split');
                $('.pdfm-convert-group').toggle(toolParam === 'convert');

                // Show correct help text
                $('.pdfm-help[data-for]').hide();
                $('.pdfm-help[data-for="' + toolParam + '"]').show();
            }
        }
    });

    // Trigger file input when button clicked
    $(document).on('click', '.pdfm-add-file', function () {
        $('#pdfm-file-input').click();
    });

    // Handle file selection
    $(document).on('change', '#pdfm-file-input', function () {
        const files = Array.from(this.files);
        if (files.length === 0) return;

        const operation = $('input[name="operation"]:checked').val();
        const validImageTypes = ['image/jpeg', 'image/png', 'image/bmp'];

        // Issue #6: Validate file count for merge
        if (operation === 'merge') {
            if (selectedFiles.length + files.length > 20) {
                alert('Maximum 20 files allowed for merge. You currently have ' + selectedFiles.length + ' files.');
                this.value = '';
                return;
            }
        }

        // Validate each file
        for (const file of files) {
            // Validate file type based on current operation
            if (operation === 'convert') {
                const direction = $('input[name="convert_direction"]:checked').val();
                if (direction === 'img-to-pdf') {
                    if (!validImageTypes.includes(file.type)) {
                        alert('Images to PDF: Only JPG, PNG, and BMP files supported.');
                        this.value = '';
                        return;
                    }
                } else {
                    // pdf-to-img requires PDF
                    if (file.type !== 'application/pdf') {
                        alert('Only PDF files are supported.');
                        this.value = '';
                        return;
                    }
                }
            } else {
                // All other operations require PDF
                if (file.type !== 'application/pdf') {
                    alert('Only PDF files are supported. Please upload .pdf files only.');
                    this.value = '';
                    return;
                }
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
                alert('File "' + file.name + '" is already added.');
                continue; // Skip this file but continue with others
            }

            // Add to array
            selectedFiles.push(file);
        }

        // GA4 Event: file_uploaded (after all files validated and added)
        if (selectedFiles.length > 0) {
            const totalSizeKB = selectedFiles.reduce((sum, f) => sum + (f.size / 1024), 0);
            trackEvent('file_uploaded', {
                tool_name: operation,
                file_size_kb: Math.round(totalSizeKB),
                file_count: selectedFiles.length
            });
        }

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

    // Service tab selector - handle click on label
    $(document).on('click', '.pdfm-tab', function () {
        const $radio = $(this).find('input[name="operation"]');
        $radio.prop('checked', true);

        const operation = $radio.val();

        // GA4 Event: tool_selected
        trackEvent('tool_selected', {
            tool_name: operation
        });

        // Toggle active state
        $('.pdfm-tab').removeClass('active');
        $(this).addClass('active');

        // Issue #2: Clear files when switching tools
        selectedFiles = [];
        updateFileList();

        // Show/hide conditional fields
        $('.pdfm-level-group').toggle(operation === 'compress');
        $('.pdfm-pages-group').toggle(operation === 'split');
        $('.pdfm-convert-group').toggle(operation === 'convert');

        // Toggle help text
        $('.pdfm-help[data-for]').hide();
        $('.pdfm-help[data-for="' + operation + '"]').show();

        // Issue #4: Update button text and file input based on operation
        const $addButton = $('.pdfm-add-file');
        if (operation === 'convert') {
            const direction = $('input[name="convert_direction"]:checked').val();
            if (direction === 'img-to-pdf') {
                $addButton.text('Add Images (JPG, PNG, BMP)');
                $('#pdfm-file-input').attr('accept', 'image/jpeg,image/png,image/bmp').attr('multiple', 'multiple');
            } else {
                $addButton.text('Add PDF File');
                $('#pdfm-file-input').attr('accept', 'application/pdf').removeAttr('multiple');
            }
        } else if (operation === 'merge') {
            $addButton.text('Add PDF Files (2-20 files)');
            $('#pdfm-file-input').attr('accept', 'application/pdf').attr('multiple', 'multiple');
        } else {
            $addButton.text('Add PDF File');
            $('#pdfm-file-input').attr('accept', 'application/pdf').removeAttr('multiple');
        }
    });

    // Also handle direct change event (keyboard navigation)
    $(document).on('change', 'input[name="operation"]', function () {
        const operation = $(this).val();

        // Toggle active state
        $('.pdfm-tab').removeClass('active');
        $(this).closest('.pdfm-tab').addClass('active');

        // Issue #2: Clear files when switching tools
        selectedFiles = [];
        updateFileList();

        // Show/hide conditional fields
        $('.pdfm-level-group').toggle(operation === 'compress');
        $('.pdfm-pages-group').toggle(operation === 'split');
        $('.pdfm-convert-group').toggle(operation === 'convert');

        // Toggle help text
        $('.pdfm-help[data-for]').hide();
        $('.pdfm-help[data-for="' + operation + '"]').show();

        // Issue #4: Update button text and file input
        const $addButton = $('.pdfm-add-file');
        if (operation === 'convert') {
            const direction = $('input[name="convert_direction"]:checked').val();
            if (direction === 'img-to-pdf') {
                $addButton.text('Add Images (JPG, PNG, BMP)');
                $('#pdfm-file-input').attr('accept', 'image/jpeg,image/png,image/bmp').attr('multiple', 'multiple');
            } else {
                $addButton.text('Add PDF File');
                $('#pdfm-file-input').attr('accept', 'application/pdf').removeAttr('multiple');
            }
        } else if (operation === 'merge') {
            $addButton.text('Add PDF Files (2-20 files)');
            $('#pdfm-file-input').attr('accept', 'application/pdf').attr('multiple', 'multiple');
        } else {
            $addButton.text('Add PDF File');
            $('#pdfm-file-input').attr('accept', 'application/pdf').removeAttr('multiple');
        }
    });

    // Convert direction toggle
    $(document).on('change', 'input[name="convert_direction"]', function () {
        const direction = $(this).val();

        $('.pdfm-direction-option').removeClass('active');
        $(this).closest('.pdfm-direction-option').addClass('active');

        // Show format selector only for pdf-to-img
        $('.pdfm-format-group').toggle(direction === 'pdf-to-img');

        // Toggle help text
        $('.pdfm-convert-help[data-for]').hide();
        $('.pdfm-convert-help[data-for="' + direction + '"]').show();

        // Issue #4: Update button text and file input based on direction
        const $addButton = $('.pdfm-add-file');
        if (direction === 'img-to-pdf') {
            $addButton.text('Add Images (JPG, PNG, BMP)');
            $('#pdfm-file-input').attr('accept', 'image/jpeg,image/png,image/bmp').attr('multiple', 'multiple');
        } else {
            $addButton.text('Add PDF File');
            $('#pdfm-file-input').attr('accept', 'application/pdf').removeAttr('multiple');
        }

        // Clear selected files when switching direction
        selectedFiles = [];
        updateFileList();
    });

    $(document).on('submit', '.pdfm-processor__form', function (event) {
        event.preventDefault();

        // Validate files added
        if (selectedFiles.length === 0) {
            alert('Please add at least one PDF file.');
            return;
        }

        const operation = $('input[name="operation"]:checked').val();

        // Split validation
        if (operation === 'split') {
            if (selectedFiles.length > 1) {
                alert('Split requires exactly 1 PDF file.');
                return;
            }
            const pages = $('#pdfm_pages').val().trim();
            if (pages === '') {
                alert('Please enter page numbers to extract.');
                return;
            }
        }

        // Merge validation (Issue #6)
        if (operation === 'merge') {
            if (selectedFiles.length < 2) {
                alert('Merge requires at least 2 PDF files.');
                return;
            }
            if (selectedFiles.length > 20) {
                alert('Merge allows maximum 20 PDF files.');
                return;
            }
        }

        // Convert validation
        if (operation === 'convert') {
            const direction = $('input[name="convert_direction"]:checked').val();
            if (direction === 'img-to-pdf') {
                // Check all files are images
                const validTypes = ['image/jpeg', 'image/png', 'image/bmp'];
                for (let file of selectedFiles) {
                    if (!validTypes.includes(file.type)) {
                        alert('Images to PDF: Only JPG, PNG, and BMP files supported.');
                        return;
                    }
                }
            } else if (direction === 'pdf-to-img') {
                if (selectedFiles.length > 1) {
                    alert('PDF to Images: Upload exactly 1 PDF file.');
                    return;
                }
                if (selectedFiles[0].type !== 'application/pdf') {
                    alert('PDF to Images: Please upload a PDF file.');
                    return;
                }
            }
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

        const compression_level = $('select[name="compression_level"]').val();

        // Build FormData with selected files
        const formData = new FormData();
        formData.append('action', 'pdfm_process_pdf');
        formData.append('nonce', pdfmProcessor.nonce);

        // Handle convert operation differently
        if (operation === 'convert') {
            const direction = $('input[name="convert_direction"]:checked').val();
            formData.append('operation', direction);
            if (direction === 'pdf-to-img') {
                formData.append('format', $('#pdfm_format').val());
            }
        } else {
            formData.append('operation', operation);
        }

        formData.append('compression_level', compression_level);
        formData.append('pages', $('#pdfm_pages').val());

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
                let spinnerText = 'Processing... (usually 5-10 seconds)';

                if (operation === 'compress') {
                    spinnerText = 'Compressing your PDF... (usually 5-10 seconds)';
                } else if (operation === 'merge') {
                    spinnerText = 'Merging your PDFs... (usually 5-10 seconds)';
                } else if (operation === 'split') {
                    spinnerText = 'Splitting your PDF... (usually 5-10 seconds)';
                } else if (operation === 'img-to-pdf') {
                    spinnerText = 'Converting images to PDF... (usually 5-10 seconds)';
                } else if (operation === 'pdf-to-img') {
                    spinnerText = 'Extracting images from PDF... (usually 5-10 seconds)';
                }

                // GA4 Event: processing_started
                const totalSizeKB = selectedFiles.reduce((sum, f) => sum + (f.size / 1024), 0);
                trackEvent('processing_started', {
                    tool_name: operation,
                    file_size_kb: Math.round(totalSizeKB),
                    file_count: selectedFiles.length
                });

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
                    const data = response.data;
                    const operation = formData.get('operation');

                    // GA4 Event: processing_complete
                    const eventParams = {
                        tool_name: operation
                    };

                    // Add compression stats if available
                    if (operation === 'compress' && data.original_size && data.compressed_size) {
                        // Parse sizes (format: "2.5 MB" or "500 KB")
                        const parseSize = (str) => {
                            const match = str.match(/([0-9.]+)\s*(KB|MB)/i);
                            if (!match) return 0;
                            const value = parseFloat(match[1]);
                            const unit = match[2].toUpperCase();
                            return unit === 'MB' ? value * 1024 : value;
                        };

                        const originalKB = parseSize(data.original_size);
                        const compressedKB = parseSize(data.compressed_size);

                        if (originalKB && compressedKB) {
                            eventParams.original_size_kb = Math.round(originalKB);
                            eventParams.processed_size_kb = Math.round(compressedKB);
                            eventParams.compression_ratio = Math.round((compressedKB / originalKB) * 100);
                        }
                    }

                    trackEvent('processing_complete', eventParams);

                    // Hide form and result container
                    $form.hide();
                    $result.hide();

                    // Store token globally for payment
                    window.pdfmDownloadToken = data.token || data.download_token;
                    window.pdfmDownloadUrl = data.downloadUrl;

                    // Store file token on modal for payment flow
                    const $modal = $('.pdfm-payment-modal');
                    $modal.attr('data-file-token', data.token);

                    // For compress operation, show stats-based success state
                    if (operation === 'compress' && data.original_size && data.compressed_size) {
                        // Populate success state stats
                        $('#pdfm-original-size').text(data.original_size);
                        $('#pdfm-compressed-size').text(data.compressed_size);
                        $('#pdfm-improvement').text(data.reduction_percent + '% smaller');

                        // Show success state with animation
                        $('#pdfm-success-state').fadeIn(400);
                    } else {
                        // For merge, split, convert - use generic success state
                        var title = 'Success!';
                        var subtitle = 'Your file is ready to download.';

                        if (operation === 'merge') {
                            title = 'Merge Successful!';
                            subtitle = 'Successfully merged ' + selectedFiles.length + ' files into one PDF.';
                        } else if (operation === 'split') {
                            const pages = formData.get('pages');
                            title = 'Split Successful!';
                            subtitle = 'Successfully extracted pages: ' + pages;
                        } else if (operation === 'img-to-pdf') {
                            title = 'Conversion Successful!';
                            subtitle = 'Converted ' + selectedFiles.length + ' images to PDF.';
                        } else if (operation === 'pdf-to-img') {
                            const format = formData.get('format') || 'JPG';
                            title = 'Conversion Successful!';
                            subtitle = 'Extracted images as ' + format.toUpperCase() + ' files.';
                        }

                        // Populate generic success state
                        $('#pdfm-generic-title').text(title);
                        $('#pdfm-generic-subtitle').text(subtitle);

                        // Show generic success state
                        $('#pdfm-success-state-generic').fadeIn(400);
                    }

                    // After successful payment, handle download
                    $(document).one('pdfm:payment:success', function (e, eventData) {
                        // Hide all success states
                        $('#pdfm-success-state').hide();
                        $('#pdfm-success-state-generic').hide();
                        $result.hide();

                        // Attach download URL to button
                        $('#pdfm-download-final').data('download-url', window.pdfmDownloadUrl);

                        // Show download success state (same for all operations)
                        $('.pdfm-download-success-state').fadeIn(300);

                        // Animate checkmark after 100ms delay
                        setTimeout(function() {
                            $('.pdfm-success-checkmark').addClass('pdfm-animate-bounce');
                        }, 100);
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

    // Try again handler - Issue #5
    $(document).on('click', '.pdfm-try-again', function () {
        selectedFiles = [];
        $('#pdfm-file-input').val(''); // Clear file input
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

    // Pay button handler (new success state)
    $(document).on('click', '#pdfm-pay-button', function (e) {
        e.preventDefault();
        if (window.pdfmDownloadToken) {
            // Set token on modal
            const $modal = $('.pdfm-payment-modal');
            $modal.attr('data-file-token', window.pdfmDownloadToken);

            // Open modal directly (same as payment-modal.js openModal())
            $modal.fadeIn(200);

            // Ensure Stripe is initialized (call from payment-modal.js namespace if available)
            if (typeof window.pdfmEnsureStripe === 'function') {
                window.pdfmEnsureStripe();
            }
        }
    });

    // Reset button handler (compress success state) - Issue #5
    $(document).on('click', '#pdfm-reset-button', function () {
        // Hide success state
        $('#pdfm-success-state').hide();

        // Show form again
        const $container = $(this).closest('.pdfm-processor');
        const $form = $container.find('.pdfm-processor__form');
        $form.show();

        // Reset form state
        selectedFiles = [];
        $('#pdfm-file-input').val(''); // Clear file input
        updateFileList();
        $form[0].reset();
        $form.find('button, input, select').prop('disabled', false);

        // Clear result container
        $container.find('.pdfm-processor__result').empty();

        // Clear stored tokens
        delete window.pdfmDownloadToken;
        delete window.pdfmDownloadUrl;
    });

    // Pay button handler (generic success state - merge/split/convert)
    $(document).on('click', '#pdfm-pay-button-generic', function (e) {
        e.preventDefault();
        if (window.pdfmDownloadToken) {
            // Set token on modal
            const $modal = $('.pdfm-payment-modal');
            $modal.attr('data-file-token', window.pdfmDownloadToken);

            // Open modal directly
            $modal.fadeIn(200);

            // Ensure Stripe is initialized
            if (typeof window.pdfmEnsureStripe === 'function') {
                window.pdfmEnsureStripe();
            }
        }
    });

    // Reset button handler (generic success state - merge/split/convert) - Issue #5
    $(document).on('click', '#pdfm-reset-button-generic', function () {
        // Hide generic success state
        $('#pdfm-success-state-generic').hide();

        // Show form again
        const $container = $(this).closest('.pdfm-processor');
        const $form = $container.find('.pdfm-processor__form');
        $form.show();

        // Reset form state
        selectedFiles = [];
        $('#pdfm-file-input').val(''); // Clear file input
        updateFileList();
        $form[0].reset();
        $form.find('button, input, select').prop('disabled', false);

        // Clear result container
        $container.find('.pdfm-processor__result').empty();

        // Clear stored tokens
        delete window.pdfmDownloadToken;
        delete window.pdfmDownloadUrl;
    });

    // Download button handler (payment success state)
    $(document).on('click', '#pdfm-download-final', function () {
        const downloadUrl = $(this).data('download-url');

        if (!downloadUrl) {
            alert('Download URL missing. Please try again.');
            return;
        }

        // GA4 Event: file_downloaded
        const operation = $('input[name="operation"]:checked').val() || 'unknown';
        trackEvent('file_downloaded', {
            tool_name: operation
        });

        // Trigger download
        window.location.href = downloadUrl;

        // Show feedback
        $(this).text('Download Started...').prop('disabled', true);
    });

    // Process Another File button (payment success state)
    $(document).on('click', '#pdfm-process-another-final', function () {
        // Reload page to reset state
        location.reload();
    });
})(jQuery);
