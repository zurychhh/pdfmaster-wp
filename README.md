# PDFMaster

WordPress-based pay-per-action PDF processing app. Users upload a PDF, process it via Stirling PDF, and pay $0.99 to download the result. No accounts; one-time payment per download.

## Quick Links

- Local Site: http://localhost:10003/
- WP Admin: http://localhost:10003/wp-admin
- Test Page: http://localhost:10003/test-processor/
- Stirling PDF: http://localhost:8080
- Repository: https://github.com/zurychhh/pdfmaster-wp

## Documentation

- For Developers: `PROJECT_STATUS.md` — Current state, architecture, next tasks
- For Content Editors: `docs/user/ELEMENTOR_EDITING_GUIDE.md` — Edit site via Elementor UI
- Session History: `docs/session_notes/` — Work logs and decisions

## Quick Start (Local Development)

Test E2E Flow: open http://localhost:10003/test-processor/

1) Upload PDF
2) Process (Compress)
3) Pay $0.99 (test card: 4242 4242 4242 4242)
4) Download

Check Stirling PDF:

```
curl http://localhost:8080/api/v1/general/health
```

View Recent PRs:

```
git log --oneline -10
```

## Pricing Model

- $0.99 per operation (pay-per-action)
- No subscriptions, no credits, no accounts
- Payment unlocks single download via server-verified token

## Tech Stack

- WordPress 6.x + Elementor Pro 3.32.2
- Stirling PDF (Docker, self-hosted)
- Stripe (test mode, PaymentIntents)

Plugins:

- `pdfmaster-processor` — Upload, validation, Stirling API, token gating
- `pdfmaster-payments` — Stripe integration, payment modal

## Payment Flow

1) User uploads → processes file (free)
2) Backend returns `download_token` (unpaid)
3) User clicks "Pay $0.99" → payment modal opens
4) Stripe PaymentIntent created (metadata: `file_token`)
5) Payment succeeds → backend marks token as paid
6) Download endpoint verifies token → streams file

## Development Workflow

### Factory.ai Droids

- Default: Single Droid (90% of tasks)
- Process: Plan → Implement → PR → Test → Merge
- Working Style: End-to-end autonomous, minimal back-and-forth

### Working with Elementor

- 100% of visual content editable via Elementor UI
- No code changes needed for content/styling
- See `docs/user/ELEMENTOR_EDITING_GUIDE.md`

### Git Workflow

```
# Work on feature branches
git checkout -b feature/task-name

# Open PR with "Droid-assisted" label
# Merge after review
```

## Important Commands

### WordPress (WP-CLI)

```
cd ~/Local\ Sites/pdfmaster/app/public
wp plugin list
wp option get pdfm_stripe_settings
tail -f wp-content/debug.log
```

### Docker (Stirling PDF)

```
docker ps | grep stirling
docker restart stirling-pdf
docker logs stirling-pdf --tail 50
```

## Repository Structure

```
wp-content/
├── plugins/
│   ├── pdfmaster-processor/     # Upload, Stirling API, token gating
│   └── pdfmaster-payments/      # Stripe integration
├── themes/
│   └── pdfmaster-theme/         # Elementor-ready theme
└── ...

docs/
├── user/                        # Self-service editing guides
│   ├── ELEMENTOR_EDITING_GUIDE.md
│   └── ELEMENTOR_STRUCTURE_MAP.md
├── session_notes/               # Historical work logs
└── archive/                     # Completed phase docs
```

## Current Status

See `PROJECT_STATUS.md` for:

- Current sprint progress
- Recent completed work
- Next tasks (priority order)
- Open PRs
- Technical details

## Contributing (Droid Sessions)

First message to Droid:

```
Hi! I'm continuing work on PDFMaster.
I've read PROJECT_STATUS.md and understand the current state.
Next task: [task name]
Ready to proceed.
```

After each session:

- Droid updates `PROJECT_STATUS.md`
- Droid creates session notes in `docs/session_notes/`
- User reviews and merges PR

---

Last Updated: 2025-10-15
