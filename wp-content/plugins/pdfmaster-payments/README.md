# PDFMaster Payments Plugin

## Purpose

Manages Stripe payments, credit tracking, and purchase flows for the PDFMaster application.

## Features

- Registers payment modal assets for Elementor-integrated UI flows.
- Scaffolds AJAX endpoint `pdfm_initiate_payment` secured with nonces.
- Placeholder Stripe payment intent creation ready for SDK integration.
- User credit tracking with safe updates via `CreditsManager`.
- Email handler stub prepared for receipt notifications.

## File Structure

```
pdfmaster-payments/
├── assets/
│   ├── css/payment-modal.css    # Modal layout and styling
│   └── js/payment-modal.js      # Modal behaviour and AJAX helpers
├── includes/
│   ├── class-credits-manager.php  # User credit management
│   ├── class-email-handler.php    # Receipt email stub
│   ├── class-payment-modal.php    # Hooks, shortcode, and AJAX handlers
│   └── class-stripe-handler.php   # TODO: Stripe API integration
├── templates/payment-modal.php  # Rendered modal markup
└── pdfmaster-payments.php       # Plugin bootstrap and autoloader
```

## Integration Notes

- Shortcode `[pdfmaster_payment_modal]` renders the modal container and loads assets.
- Use the `pdfm_stripe_publishable_key` and `pdfm_stripe_secret_key` filters to supply live credentials.
- AJAX nonce action: `pdfm_payments_nonce`.
- Extend `EmailHandler::send_receipt()` once payment confirmation webhooks are in place.

## Development

- Classes are namespaced under `PDFMaster\Payments` with PSR-4 autoloading.
- Follow WordPress Coding Standards and escape/sanitize all user-generated content.
- TODO comments mark remaining work for Stripe and email integrations.
