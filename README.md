# PDFMaster

WordPress-based pay-per-action PDF processing app. Users upload a PDF, process it via Stirling PDF, and pay $0.99 to download the result. No accounts; one-time payment per download.

**🚀 LIVE: https://www.pdfspark.app** (Production deployment on Railway + Vercel)

## Quick Links

### Production
- **Live Site:** https://www.pdfspark.app
- **Services Page:** https://www.pdfspark.app/services
- **WordPress Admin:** https://www.pdfspark.app/wp-admin
- **Stripe Dashboard:** https://dashboard.stripe.com (Live mode active)

### Local Development
- Local Site: http://localhost:10003/
- WP Admin: http://localhost:10003/wp-admin
- Test Page: http://localhost:10003/test-processor/
- Stirling PDF: http://localhost:8080

### Repository
- GitHub: https://github.com/zurychhh/pdfmaster-wp
- Branch: `main`
- Deployment: Railway (backend) + Vercel (frontend proxy)

## Current Status

**✅ PRODUCTION READY**
- Deployed to Railway with custom domain (www.pdfspark.app)
- Stripe Live mode activated ($0.99 real payments)
- All 4 tools working (compress, merge, split, convert)
- Professional UX with payment flow
- Terms & Conditions page live
- CORS issues resolved
- Domain configuration complete

## Documentation

- **For Developers:** `PROJECT_STATUS.md` — Current state, architecture, deployment details
- **For Content Editors:** `docs/user/ELEMENTOR_EDITING_GUIDE.md` — Edit site via Elementor UI
- **For Business:** See "Business Model" section below
- **Session History:** `docs/session_notes/` — Work logs and technical decisions

## Quick Start (Local Development)

Test E2E Flow: open http://localhost:10003/test-processor/

1. Upload PDF
2. Process (Compress)
3. Pay $0.99 with test card: `4242 4242 4242 4242`
4. Download

Check Stirling PDF health:
```bash
curl http://localhost:8080/api/v1/general/health
```

View recent commits:
```bash
git log --oneline -10
```

## Available Tools

1. **Compress PDF** - Reduce file size (3 quality levels)
2. **Merge PDFs** - Combine multiple PDFs into one (2-20 files)
3. **Split PDF** - Extract specific pages from PDF
4. **Convert** - Bidirectional image conversion:
   - Images → PDF (JPG, PNG, BMP)
   - PDF → Images (JPG, PNG formats)

**All tools feature:**
- Animated success states with checkmark
- Professional payment modal with Stripe Elements
- Post-payment download screen
- Mobile responsive design
- File validation and error handling

## Business Model

### Pricing
- **$0.99 per operation** (pay-per-action)
- No subscriptions, no credits, no accounts
- Payment unlocks single download via server-verified token
- Stripe Live mode (production payments)

### Revenue Model
- Month 1 Target: 20-40 conversions
- Expected Revenue: $20-40 (validation)
- Conversion Rate Target: 40-50% (process → pay)

### Value Proposition
- **Simplicity:** No signup, instant processing
- **Privacy:** Files auto-deleted after processing
- **Transparent:** Single flat price, no hidden costs
- **Fast:** Real-time processing via Stirling PDF

## Tech Stack

### Production Infrastructure
- **Frontend Proxy:** Vercel (handles www.pdfspark.app)
- **Backend:** Railway (WordPress + Stirling PDF)
- **Database:** MySQL on Railway
- **CDN:** Vercel Edge Network
- **SSL:** Automatic HTTPS via Vercel

### WordPress Stack
- WordPress 6.x + PHP 8.4
- Elementor Pro 3.32.2 (content pages)
- Custom PHP templates (performance pages)
- FrankenPHP (high-performance PHP server)

### Payment Processing
- Stripe (Live mode)
- PaymentIntents API
- Test/Live mode toggle in WordPress admin
- Server-side token verification

### PDF Processing
- Stirling PDF (Docker, self-hosted on Railway)
- Internal Railway network communication
- 4 endpoints: compress, merge, split, convert

### Custom Plugins
- `pdfmaster-processor` — Upload, validation, Stirling API, token gating
- `pdfmaster-payments` — Stripe integration, payment modal, admin settings

## Payment Flow

1. User uploads → processes file (free)
2. Backend returns `download_token` (unpaid)
3. User clicks "Pay $0.99" → payment modal opens
4. Stripe PaymentIntent created (metadata: `file_token`)
5. Payment succeeds → backend marks token as paid
6. Download endpoint verifies token → streams file
7. File auto-deleted after download

**Security:**
- Server-side token verification
- No client-side bypass possible
- Stripe webhook for payment confirmation
- Railway environment variables for sensitive keys

## Deployment Architecture

```
User Request
    ↓
Vercel (www.pdfspark.app)
    ↓ [proxy]
Railway (pdfmaster-wp-production.up.railway.app)
    ├── WordPress (FrankenPHP on :8080)
    └── Stirling PDF (Docker, internal network)
```

**Key Components:**
- Vercel: Handles custom domain, SSL, CDN
- Railway: Hosts WordPress + Stirling PDF
- MySQL: Railway internal database
- Stripe: External payment API

**Environment Configuration:**
- `WP_HOME` / `WP_SITEURL`: https://www.pdfspark.app
- `STRIPE_PUBLISHABLE_KEY`: pk_live_... (production)
- `STRIPE_SECRET_KEY`: sk_live_... (production)
- `STIRLING_API_URL`: http://stirling-pdf.railway.internal:8080

## Development Workflow

### Working Style
- **Claude Code** (AI assistant) handles end-to-end development
- User provides: approvals, API keys, strategic decisions
- Documentation updated every session

### Template Strategy
- **Custom PHP templates** when: speed critical, complex features
- **Elementor** when: frequent content updates, non-developer editing
- User explicitly accepted zero Elementor editability for Homepage P1 (speed priority)

### Git Workflow
```bash
# Work on feature branches
git checkout -b feature/task-name

# Commit with descriptive messages
git commit -m "feat: description"

# Push and create PR
git push origin feature/task-name
```

### Deployment Workflow
1. Push to `main` branch on GitHub
2. Railway auto-deploys (3-5 min build time)
3. Verify at https://www.pdfspark.app
4. Monitor Railway logs if issues

## Important Commands

### WordPress (WP-CLI)
```bash
cd ~/Local\ Sites/pdfmaster/app/public
wp plugin list
wp option get pdfm_stripe_settings
tail -f wp-content/debug.log
```

### Docker (Stirling PDF)
```bash
docker ps | grep stirling
docker restart stirling-pdf
docker logs stirling-pdf --tail 50
```

### Railway (Deployment)
```bash
railway login
railway link  # Link to project
railway status
railway logs --tail 50
railway variables  # View environment variables
```

### Database (Railway MySQL)
```bash
# Access Railway MySQL directly
mysql -h interchange.proxy.rlwy.net -P 22656 -u root -p railway

# Common queries
SELECT option_value FROM wp_options WHERE option_name='siteurl';
SELECT option_value FROM wp_options WHERE option_name='pdfm_stripe_settings';
```

## Repository Structure

```
wp-content/
├── plugins/
│   ├── pdfmaster-processor/     # Upload, Stirling API, token gating
│   └── pdfmaster-payments/      # Stripe integration
├── themes/
│   └── pdfmaster-theme/         # Custom template + Elementor
└── mu-plugins/
    ├── pdfm-railway-config.php  # Railway env var handling
    └── force-domain.php         # Domain redirect prevention

docs/
├── user/                        # Self-service editing guides
├── session_notes/               # Historical work logs
└── archive/                     # Completed phase docs

Deployment Files:
├── Dockerfile                   # Railway container
├── Caddyfile                    # FrankenPHP config
├── vercel.json                  # Vercel proxy config
└── .vercelignore               # Vercel deployment config
```

## Production Monitoring

### Health Checks
```bash
# Check site status
curl -I https://www.pdfspark.app

# Check Stirling PDF (via Railway)
railway run curl http://stirling-pdf.railway.internal:8080/api/v1/general/health
```

### Payment Monitoring
- Stripe Dashboard: https://dashboard.stripe.com
- Live payments visible in real-time
- Webhook events: payment_intent.succeeded

### Error Tracking
```bash
# WordPress debug log (local)
tail -f wp-content/debug.log

# Railway logs (production)
railway logs --tail 100 --follow
```

## Common Issues & Solutions

### Issue: CORS Error
**Symptom:** Frontend can't call admin-ajax.php
**Solution:** WordPress URLs must match access domain (www.pdfspark.app)
**Fix:** Update `wp_options` table: `home` and `siteurl`

### Issue: Redirect Loop
**Symptom:** ERR_TOO_MANY_REDIRECTS
**Solution:** Check `WP_HOME`/`WP_SITEURL` constants in wp-config.php
**Fix:** Ensure constants match domain (with www prefix)

### Issue: Test Mode Error in Live Mode
**Symptom:** "Request made in test mode" with real card
**Solution:** Railway env vars contain test keys
**Fix:** Update Railway env vars to use `pk_live_` and `sk_live_` keys

### Issue: Payment Modal Not Opening
**Symptom:** Button click doesn't show modal
**Solution:** Stripe.js not loaded or wrong key
**Fix:** Check browser console, verify publishable key in WordPress admin

## Recent Major Updates (2025-10-24)

### Production Deployment ✅
- Deployed to Railway with FrankenPHP + Stirling PDF
- Custom domain configured: www.pdfspark.app
- Vercel proxy for CDN and SSL
- All 4 tools functional in production

### Stripe Live Mode ✅
- Test/Live mode toggle added to WordPress admin
- Railway environment variables updated with live keys
- Production payments working ($0.99 real charges)
- Webhook configured for payment confirmations

### Domain & URL Fixes ✅
- Fixed redirect loop (WP_HOME/WP_SITEURL)
- Fixed CORS errors (domain mismatch)
- Updated all internal URLs to www.pdfspark.app
- Terms & Conditions page added

### Technical Improvements ✅
- Must-use plugins for configuration overrides
- Environment-aware Stripe key selection
- Database optimization for production
- Security hardening (domain forcing, token verification)

## Success Metrics

### MVP Definition (✅ Completed)
- ✅ All 4 tools working (compress, merge, split, convert)
- ✅ Payment integration functional (Stripe Live mode)
- ✅ E2E flow tested (upload → process → pay → download)
- ✅ Real file processing (Stirling PDF)
- ✅ Production deployment (Railway + Vercel)
- ✅ Custom domain with SSL

### Next Milestones
- 🎯 First 20 paying users
- 🎯 40-50% conversion rate (process → pay)
- 🎯 $20-40 revenue in month 1
- 🎯 Performance optimization (< 2s page load)
- 🎯 SEO optimization (Google indexing)

## Contributing (Development Sessions)

### First Message to AI Assistant
```
Hi! I'm continuing work on PDFMaster.

I've read:
- PROJECT_STATUS.md (current state)
- Recent session notes

Current environment:
- Production: https://www.pdfspark.app
- Local: http://localhost:10003

Next task: [specify task]
Ready to proceed.
```

### After Each Session
- Update `PROJECT_STATUS.md` with changes
- Create session notes in `docs/session_notes/`
- Commit changes with descriptive messages
- Push to GitHub (auto-deploys to Railway)

## Important Links

### Production
- **Live Site:** https://www.pdfspark.app
- **Admin:** https://www.pdfspark.app/wp-admin
- **Services:** https://www.pdfspark.app/services

### Development
- **Repository:** https://github.com/zurychhh/pdfmaster-wp
- **Railway Dashboard:** https://railway.app
- **Vercel Dashboard:** https://vercel.com
- **Stripe Dashboard:** https://dashboard.stripe.com

### Documentation
- **Project Status:** PROJECT_STATUS.md
- **Comprehensive Docs:** docs/PDFMASTER_PROJECT_DOCS.md
- **Session Notes:** docs/session_notes/

### API Documentation
- **Stirling PDF Swagger:** http://localhost:8080/swagger-ui/index.html (local)
- **Stripe API Docs:** https://stripe.com/docs/api

---

**Last Updated:** 2025-10-24
**Status:** ✅ Production Ready
**Live URL:** https://www.pdfspark.app
**Revenue Status:** $0 (just launched - awaiting first customers)
