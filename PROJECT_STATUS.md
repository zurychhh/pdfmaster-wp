# PDFMaster - Project Status & Handoff Documentation

Last updated: 2025-10-09

---

## 🎯 Project Overview

What: PDFMaster - WordPress pay-per-action PDF processing app
Business Model: $0.99 per file processed (no subscriptions, no credits)
Tech Stack: WordPress + Elementor Pro + Stirling PDF + Stripe
Goal: MVP with compress tool, working payment flow

Repository: https://github.com/zurychhh/pdfmaster-wp
Branch: main (active feature branch: feature/pay-per-action-99)
Last PR: #7 — Pay‑per‑action + payment modal fixes (OPEN)

---

## ✅ What Works (Current State)

### Core Features WORKING
- ✅ WordPress 6.x + Elementor Pro 3.32.2 running
- ✅ Stirling PDF (Docker on :8080) — compress endpoint functional
- ✅ Test page: http://localhost:10003/test-processor/
- ✅ Upload → Compress → Success flow working
- ✅ Compression verified: 3.3MB → ~500KB (real compression)

### Payment Integration WORKING
- ✅ Stripe.js loads correctly (in HEAD)
- ✅ Payment modal opens with Stripe Elements
- ✅ PaymentIntent creation ($0.99)
- ✅ Token-based download gating (server-side)
- ✅ E2E tested: upload → pay → download works
- ✅ Test card (4242...) processes successfully

### Recent Completed Work
- ✅ Removed credits system → $0.99 pay‑per‑action
- ✅ Fixed Stirling API endpoint: /api/v1/misc/compress-pdf
- ✅ Payment modal Stripe integration complete
- ✅ Server-side token verification before download
- ✅ E2E flow tested and verified

---

## 📁 File Structure

### Backend — WordPress Plugins

#### pdfmaster-processor

wp-content/plugins/pdfmaster-processor/
├── pdfmaster-processor.php (main plugin file)
├── includes/
│   ├── class-file-handler.php (validation, storage)
│   ├── class-stirling-api.php (Stirling PDF API integration)
│   │   └── compress(): /api/v1/misc/compress-pdf
│   └── class-processor.php (AJAX handler, token generation, download gating)
└── assets/
    ├── js/processor-scripts.js (upload flow, payment trigger)
    └── css/processor-styles.css

Key functions:
- Upload & validation
- Stirling API calls (compress)
- Token generation for secure download
- AJAX endpoint: pdfm_process_pdf
- Download endpoint: pdfm_download (verifies payment)

#### pdfmaster-payments

wp-content/plugins/pdfmaster-payments/
├── pdfmaster-payments.php (main plugin file)
├── composer.json (Stripe PHP SDK)
├── includes/
│   ├── class-stripe-handler.php (PaymentIntent, webhook, confirm)
│   ├── class-payment-modal.php (shortcode, script loading, localization)
│   └── admin/
│       └── class-payments-admin.php (settings page)
└── assets/
    ├── js/payment-modal.js (Stripe Elements, payment flow)
    └── css/payment-modal.css

Key functions:
- Stripe PaymentIntent creation ($0.99)
- Payment confirmation & token marking
- Webhook handler
- Admin settings (Stripe keys)
- AJAX endpoints: pdfm_create_payment_intent, pdfm_confirm_payment

### Frontend — Theme

wp-content/themes/pdfmaster-theme/
├── style.css
├── functions.php (design tokens, global colors/typography)
└── assets/css/home-polish.css

### Test Page
- Page ID: 12
- URL: http://localhost:10003/test-processor/
- Shortcodes: [pdfmaster_processor] + [pdfmaster_payment_modal]

---

## 🔧 Technical Details

### Payment Flow
1) User uploads PDF → processes via Stirling
2) Backend returns download_token (unique, unpaid)
3) Frontend shows "Pay $0.99 to Download" button
4) Button opens payment modal (Stripe Elements)
5) PaymentIntent created with file_token in metadata
6) User enters card → payment succeeds
7) Backend marks token as paid in database
8) Download button activates
9) Download endpoint verifies token payment before streaming file

Security: Token-based gating, server-side verification, no client-side bypass

### Stirling PDF Integration
- Endpoint: http://localhost:8080/api/v1/misc/compress-pdf
- Method: POST multipart/form-data
- Required fields:
  - fileInput: PDF file
  - optimizeLevel: 5 (1–9)
  - expectedOutputSize: 25KB
  - linearize: false
  - normalize: false
  - grayscale: false
- Response: Compressed PDF binary

### Stripe Configuration
- Mode: Test
- Keys in: WP Admin → Settings → PDFMaster Payments
- Amount: 99 cents ($0.99)
- Test card: 4242 4242 4242 4242
- Dashboard: https://dashboard.stripe.com/test/payments

---

## 🔄 Recent Changes (What Changed)

### Payment Model Simplification
Before: 3 credits for $2.90 with user accounts
After: $0.99 per action, no credits, no accounts

Changed files:
- class-stripe-handler.php: amount 290 → 99; metadata includes file_token
- class-processor.php: removed credit checks; download verifies paid token
- payment-modal.js: simplified messaging and flow using file_token

### Stirling API Fix
Before: Wrong endpoint causing 404
After: Correct endpoint /api/v1/misc/compress-pdf with required params

### Payment Modal Fix
Before: Stripe.js not loading, modal not opening
After: Proper script loading order, event handlers working

---

## 🐛 Known Issues (Non-Blocking)

Minor UI Issue
- Description: After payment success, some buttons (cancel/download under main content) don't refresh
- Impact: LOW — user can still download via primary button
- Fix: Will be addressed in UX polish task
- Status: Backlogged

---

## 🚀 How to Continue Work

### Quick Start

Verify Environment
```
# WordPress
open http://localhost:10003/wp-admin

# Stirling PDF health
curl http://localhost:8080/api/v1/general/health

# Docker
docker ps | grep stirling
```

Run E2E Test
```
open http://localhost:10003/test-processor/
# 1. Upload PDF
# 2. Compress
# 3. Pay $0.99 (card: 4242 4242 4242 4242)
# 4. Download
```

Check Recent PRs
```
git log --oneline -10
# or visit: https://github.com/zurychhh/pdfmaster-wp/pulls
```

### Next Tasks (Priority)

Immediate (Docs)
- Update any remaining documentation references ($2.90/credits → $0.99/action)

Soon (UX Polish)
- Fix secondary buttons refresh after payment
- Add compression level selector (low/med/high)
- Progress indicators during processing
- File size preview before/after
- Better error messages

Add Merge Tool (Feature)
- Multi-file upload, reorder, Stirling merge endpoint, same $0.99 payment flow

Later (Split Tool)
- Page range selector, preview, Stirling split API integration

Production Prep
- Domain & SSL, production Stripe keys, monitoring (Sentry), backups

---

## 🔑 Important Commands

WordPress (WP-CLI)
```
cd ~/Local\ Sites/pdfmaster/app/public
wp plugin list
wp option get pdfm_stripe_settings
tail -f wp-content/debug.log
```

Docker (Stirling PDF)
```
docker ps | grep stirling
docker restart stirling-pdf
docker logs stirling-pdf --tail 50
```

Git
```
git branch
git log --oneline -5
git pull origin main
```

---

## 📊 Success Metrics

MVP Definition
- ✅ One tool (compress) working
- ✅ Payment integration functional
- ✅ E2E flow tested
- ✅ Real file processing (not stub)

Business Validation
- Target: 20–40 conversions in month 1
- Revenue: $20–40 (validation)
- Conversion rate target: 40–50% (process → pay)

---

## 🔗 Important Links

- Repository: https://github.com/zurychhh/pdfmaster-wp
- Test Page: http://localhost:10003/test-processor/
- WP Admin: http://localhost:10003/wp-admin
- Stirling PDF: http://localhost:8080
- Stirling Swagger: http://localhost:8080/swagger-ui/index.html
- Stripe Dashboard: https://dashboard.stripe.com/test/payments

---

## 💡 Development Notes

Factory.ai Workflow
- Default: Single Droid (90% of cases)
- Multi-Droid only if: task > 6h, independent components, zero overlap, >30% time savings

Code Standards
- WordPress-compatible code, clear structure
- Explicit require/include where applicable

Testing Protocol
- Always test E2E after major changes
- Use real PDFs (not tiny dummies)
- Verify Stripe test payment; confirm record in Stripe Dashboard

---

## 🤖 For Next Droid Session

First message suggestion:
"Hi! I'm continuing work on PDFMaster.
I've read PROJECT_STATUS.md and understand current state, structure, and recent changes.
Next task I'd like to start: [task]. Ready to proceed — please confirm."

This file should be updated before each session handoff to maintain accurate project state.
