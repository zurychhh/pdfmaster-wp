# PDFMaster Payments Plugin

## Purpose

Handles Stripe pay-per-action payments and the purchase flow for the PDFMaster application ($0.99 per action).

## Features

- Registers payment modal assets for Elementor-integrated UI flows.
- AJAX endpoints for Stripe PaymentIntents: `pdfm_create_payment_intent` and `pdfm_confirm_payment` (nonce-protected).
- PaymentIntent metadata links the processed file token; on success, server marks the token as paid.
- Email handler stub for future receipts/notifications.

## File Structure

```
pdfmaster-payments/
├── assets/
│   ├── css/payment-modal.css    # Modal layout and styling
│   └── js/payment-modal.js      # Stripe Elements payment flow helpers
├── includes/
│   ├── class-email-handler.php    # Receipt email stub (optional)
│   ├── class-payment-modal.php    # Hooks, shortcode, and AJAX handlers
│   └── class-stripe-handler.php   # Stripe API integration (PaymentIntents)
├── templates/payment-modal.php  # Rendered modal markup
└── pdfmaster-payments.php       # Plugin bootstrap and autoloader
```

## Integration Notes

- Shortcode `[pdfmaster_payment_modal]` renders the modal container and loads assets.
- Settings page stores Stripe API keys (test/live) in WP options.
- AJAX nonce action: `pdfm_payments_nonce`.
- Extend `EmailHandler::send_receipt()` once payment confirmation webhooks are in place.

## Payment Model

- Pricing: $0.99 per operation (pay‑per‑action)
- No accounts or credit balances; payment success unlocks a single download via a server-verified token.

## Development

- Classes are namespaced under `PDFMaster\Payments` with PSR-4 autoloading.
- Follow WordPress Coding Standards and escape/sanitize all user-generated content.
- See `PROJECT_STATUS.md` for current endpoints and flow details.
