# PDFMaster

WordPress-based pay-per-action PDF processing app. Users upload a PDF, process it via Stirling PDF, and pay $0.99 to download the result. No accounts; one‑time payment per download.

## Pricing Model

- $0.99 per operation (pay‑per‑action)
- Payment success unlocks a single download via a server-verified token

## Components

- pdfmaster-processor (plugin): upload/validation, Stirling API calls, token generation and download gating
- pdfmaster-payments (plugin): Stripe PaymentIntents, payment modal, webhook/confirmation
- pdfmaster-theme (theme): minimal Elementor‑ready theme

## Quick Start (Local)

1) Open: http://localhost:10003/test-processor/
2) Upload a PDF and process (Compress)
3) Pay $0.99 with Stripe test card `4242 4242 4242 4242`
4) Download after payment succeeds

Stirling PDF: http://localhost:8080 (endpoint: `/api/v1/misc/compress-pdf`)

## Payment Flow

1) Process file → backend returns `download_token`
2) Open payment modal → create and confirm Stripe PaymentIntent (metadata includes file token)
3) Server marks token as paid on success
4) Download endpoint verifies token before streaming file

## Development Notes

- See `PROJECT_STATUS.md` for current status, endpoints, and next tasks
- WordPress + Elementor Pro, Stripe (test mode), Stirling PDF (Docker)

## Links

- Project Status: ./PROJECT_STATUS.md
- WP Admin: http://localhost:10003/wp-admin
- Stirling Swagger: http://localhost:8080/swagger-ui/index.html
- Stripe Dashboard (test): https://dashboard.stripe.com/test/payments

## Collaboration Workflow (Working Style)

- Assistant (Droid) executes end‑to‑end tasks proactively: plans, implements, commits on feature branches, opens PRs, and validates (lint/build/tests) without waiting for hand‑offs.
- You (Rafał) only handle items that require privileged access or cannot be automated (e.g., providing API keys/secrets, approving PRs if desired).
- Default rules:
  - Start from a clean git state; work on feature branches; open PRs marked “Droid‑assisted”.
  - Avoid manual panel edits when possible; prefer code (hooks/templates/scripts). If panel edits are required (e.g., Elementor), Droid simulates via safe injections and documents steps.
  - Minimal back‑and‑forth: Droid does “everything possible,” asking only for blockers or secrets.
