# PDFMaster - Current Architecture

**Last Updated:** 2025-11-12
**Version:** 1.0 (Post-cleanup)

---

## System Overview

**PDFMaster** is a WordPress-based PDF processing application with a pay-per-action business model ($0.99 per operation). The system is built on Railway (backend) + Vercel (frontend proxy) infrastructure.

**Live URL:** https://www.pdfspark.app

---

## Infrastructure Stack

```
User Request
    ↓
Vercel (www.pdfspark.app) — CDN, SSL, Edge Network
    ↓ [proxy]
Railway (pdfmaster-wp-production.up.railway.app)
    ├── WordPress + FrankenPHP (:8080)
    ├── MySQL Database (internal)
    └── Stirling PDF (Docker, internal network)
```

### Components

| Component | Technology | Purpose |
|-----------|-----------|---------|
| Frontend Proxy | Vercel | CDN, SSL termination, domain routing |
| Application Server | FrankenPHP (PHP 8.4) | High-performance WordPress hosting |
| Database | MySQL on Railway | WordPress data, payment tokens |
| PDF Processing | Stirling PDF (Docker) | File operations (compress, merge, split, convert) |
| Payment Gateway | Stripe Live | Real payment processing ($0.99) |

---

## Application Architecture

### WordPress Core
- **Version:** 6.x
- **PHP:** 8.4
- **Theme:** `pdfmaster-theme` (custom PHP templates)
- **Server:** FrankenPHP (Caddy-based)

### Custom Plugins

#### 1. pdfmaster-processor
**Location:** `wp-content/plugins/pdfmaster-processor/`

**Responsibilities:**
- File upload and validation
- Stirling PDF API integration (4 operations)
- Download token generation and verification
- AJAX endpoints for processing

**Key Classes:**
- `File_Handler` — Upload validation, storage
- `Stirling_API` — API integration (compress, merge, split, convert)
- `Processor` — Main controller, token management

**Endpoints:**
- `/wp-admin/admin-ajax.php?action=pdfm_process_pdf` — Process files
- `/wp-admin/admin-ajax.php?action=pdfm_download` — Download with token verification

#### 2. pdfmaster-payments
**Location:** `wp-content/plugins/pdfmaster-payments/`

**Responsibilities:**
- Stripe PaymentIntent creation
- Payment confirmation and token marking
- Webhook handling
- Admin settings interface

**Key Classes:**
- `Stripe_Handler` — Payment processing
- `Payment_Modal` — Frontend modal rendering
- `Payments_Admin` — Settings page

**Dependencies:**
- `stripe/stripe-php` (via Composer)

### Must-Use Plugins

**Location:** `wp-content/mu-plugins/`

- `pdfm-railway-config.php` — Railway environment variable handling
- `force-domain.php` — Domain redirect prevention (www.pdfspark.app)

---

## Payment Flow

```
1. User uploads PDF → Processor validates → Stirling PDF processes
2. Backend returns { download_token: "xyz123", file_url: "...", stats: {...} }
3. Frontend displays success state + "Pay $0.99" button
4. User clicks → Payment modal opens (Stripe Elements)
5. Stripe PaymentIntent created (metadata: file_token = "xyz123")
6. User completes payment → Stripe confirms
7. Backend marks token as "paid" in database
8. Download button activates
9. User downloads → Token verified → File streamed
10. File auto-deleted after download
```

**Security:**
- Server-side token verification (no client-side bypass)
- Single-use tokens
- Payment confirmation before download
- Railway environment variables for sensitive keys

---

## File Structure

```
wp-content/
├── plugins/
│   ├── pdfmaster-processor/
│   │   ├── includes/
│   │   │   ├── class-file-handler.php
│   │   │   ├── class-stirling-api.php
│   │   │   └── class-processor.php
│   │   └── assets/
│   │       ├── js/processor-scripts.js
│   │       └── css/processor-styles.css
│   │
│   └── pdfmaster-payments/
│       ├── includes/
│       │   ├── class-stripe-handler.php
│       │   ├── class-payment-modal.php
│       │   └── admin/class-payments-admin.php
│       └── assets/
│           ├── js/payment-modal.js
│           └── css/payment-modal.css
│
├── themes/
│   └── pdfmaster-theme/
│       ├── functions.php (design tokens, enqueuing)
│       ├── page-homepage-p1.php (custom template)
│       └── assets/
│           ├── css/ (homepage-p1.css, processor-styles.css)
│           └── js/ (homepage-p1.js)
│
└── mu-plugins/
    ├── pdfm-railway-config.php
    └── force-domain.php
```

---

## Stirling PDF Integration

**Container:** Docker (http://stirling-pdf.railway.internal:8080)

**Endpoints:**
- `/api/v1/misc/compress-pdf` — Compress (9 quality levels)
- `/api/v1/general/merge-pdfs` — Merge multiple PDFs
- `/api/v1/general/split-pages` — Split by page range
- `/api/v1/convert/img/pdf` — Images → PDF
- `/api/v1/convert/pdf/img` — PDF → Images

**Communication:** Internal Railway network (not exposed publicly)

---

## Environment Configuration

### Railway Environment Variables

```bash
# WordPress URLs
WP_HOME=https://www.pdfspark.app
WP_SITEURL=https://www.pdfspark.app

# Stripe (Live Mode)
STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...

# Stirling PDF
STIRLING_API_URL=http://stirling-pdf.railway.internal:8080

# Monitoring (to be configured)
SENTRY_DSN=[pending setup]
```

### Vercel Configuration

**File:** `vercel.json`

```json
{
  "rewrites": [
    { "source": "/(.*)", "destination": "https://pdfmaster-wp-production.up.railway.app/$1" }
  ]
}
```

---

## Database Schema (Relevant Tables)

### wp_pdfmaster_tokens
Stores download tokens with payment status.

| Column | Type | Purpose |
|--------|------|---------|
| id | bigint(20) | Primary key |
| token | varchar(255) | Unique download token |
| file_path | text | Path to processed file |
| paid | tinyint(1) | Payment status (0/1) |
| created_at | datetime | Token creation time |

### wp_options
WordPress settings including Stripe configuration.

**Key:** `pdfm_stripe_settings`
**Value:** Serialized array with Stripe keys and test mode flag

---

## Monitoring & Operations

### Health Checks
**Endpoint:** `/wp-json/pdfmaster/v1/health`

**Monitors:**
- WordPress availability
- MySQL connectivity
- Stirling PDF status

### Error Tracking
**Tool:** Sentry (pending setup)
**Captures:** PHP errors, Stripe failures, Stirling API errors

### Backups
**Scripts:** `scripts/backup-db.sh`, `scripts/restore-db.sh`
**Frequency:** Manual (automated backups pending Railway configuration)

---

## Deployment Workflow

1. **Push to GitHub** → `main` branch
2. **Railway Auto-Deploy** → 3-5 minute build
3. **Vercel Auto-Deploy** → Instant (proxy config)
4. **Verification** → Check https://www.pdfspark.app

**Deployment Files:**
- `Dockerfile` — Railway container configuration
- `Caddyfile` — FrankenPHP server configuration
- `vercel.json` — Vercel proxy rules

---

## Performance Optimizations

- Custom PHP templates (no page builder overhead)
- FrankenPHP (faster than Apache/Nginx PHP-FPM)
- Vercel Edge Network (global CDN)
- Asset minification and versioning
- Hardware-accelerated CSS animations

---

## Security Measures

1. **Payment Security**
   - Server-side token verification
   - Stripe webhook signature validation
   - No client-side payment bypass possible

2. **Domain Security**
   - Forced HTTPS via Vercel
   - Domain forcing in wp-config.php
   - CORS configuration

3. **File Security**
   - Upload validation (file type, size)
   - Temporary storage with auto-deletion
   - Single-use download tokens

---

## Tech Stack Summary

| Layer | Technology | Version |
|-------|-----------|---------|
| Frontend | Vercel Proxy | Latest |
| Backend | WordPress | 6.x |
| Language | PHP | 8.4 |
| Server | FrankenPHP | Latest |
| Database | MySQL | 8.x |
| PDF Processing | Stirling PDF | Latest (Docker) |
| Payments | Stripe | Live API |
| Hosting | Railway + Vercel | Cloud |

---

## References

- **Production:** https://www.pdfspark.app
- **Repository:** https://github.com/zurychhh/pdfmaster-wp
- **Documentation:** PROJECT_STATUS.md, README.md
- **Stripe Dashboard:** https://dashboard.stripe.com
- **Railway Dashboard:** https://railway.app

---

**Architecture Status:** ✅ Stable and production-ready
**Last Major Update:** 2025-10-30 (Production hardening complete)
