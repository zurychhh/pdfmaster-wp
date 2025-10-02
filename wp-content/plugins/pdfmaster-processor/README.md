# PDFMaster Processor Plugin

## Purpose

Provides the upload, validation, processing, and lifecycle management for PDF files within the PDFMaster application. Processing will be delegated to the Stirling PDF API in future iterations.

## Features

- Secure upload handling with server-side validation and storage segregation.
- AJAX endpoint scaffolding for Elementor-driven interfaces.
- Scheduled cleanup to automatically purge processed artefacts after one hour (filterable).
- Placeholder Stirling PDF API wrapper for upcoming integration.
- Enqueued scripts/styles for the processor UI with localized AJAX configuration.

## File Structure

```
pdfmaster-processor/
├── assets/
│   ├── css/processor-styles.css   # Base styling for processor UI components
│   └── js/processor-scripts.js    # AJAX handlers and front-end hooks
├── includes/
│   ├── class-cleanup.php          # Purges stored files after expiration
│   ├── class-file-handler.php     # Validates and stores uploads
│   ├── class-processor.php        # Registers hooks, AJAX, and shortcode scaffolding
│   └── class-stirling-api.php     # TODO: Stirling PDF API client
├── tests/test-file-handler.php    # PHPUnit placeholder awaiting WP bootstrap
└── pdfmaster-processor.php        # Plugin bootstrap, autoloader, and activation hooks
```

## Integration Notes

- AJAX action: `pdfm_process_pdf` (requires nonce `pdfm_processor_nonce`).
- Shortcode: `[pdfmaster_processor]` renders the default upload form; Elementor widgets should consume the enqueued assets.
- Filter `pdfm_stirling_api_endpoint` allows configuration of the Stirling API base URL.
- Filter `pdfm_processor_file_expiration` can adjust deletion delays.

## Development

- Namespaced classes under `PDFMaster\Processor` with `pdfm_`-prefixed helper functions.
- Follow WordPress Coding Standards (PHPCS) and sanitize/escape all user input.
- Replace TODO markers once the Stirling PDF API credentials and flows are available.
