# PDFMaster - Unified Project Documentation

**Version:** 3.0
**Last Updated:** 2025-10-21
**Status:** Active Development (MVP 85% → Production)

---

## 📚 Table of Contents

1. [Project Overview](#project-overview)
2. [Current Status](#current-status)
3. [Technical Architecture](#technical-architecture)
4. [Working Methodology](#working-methodology)
5. [Getting Started](#getting-started)
6. [Next Steps](#next-steps)
7. [Quick Reference](#quick-reference)

---

## 🎯 Project Overview

### What We're Building

**Product:** PDFMaster - Web-based PDF processing application
**Business Model:** $0.99 per action (no subscriptions, no credits, no accounts)
**Tech Stack:** WordPress + Custom PHP Templates + Stirling PDF (Docker) + Stripe
**Target:** $500-2000 MRR in Year 1

### The Problem

Users need occasional PDF processing (compress, merge, split, convert) but:
- Adobe Acrobat: $20-30/month (expensive for occasional use)
- Freemium tools: Limited features, privacy concerns
- Desktop software: Installation overhead, slow

### Our Solution

- **Pay-per-use:** $0.99 per operation (no recurring charges)
- **Browser-based:** No installation needed
- **Secure:** Files auto-deleted after 1 hour
- **Fast:** Processing in 5-10 seconds

### Success Metrics

**MVP Definition:**
- ✅ Compress tool working end-to-end
- ✅ Payment integration ($0.99 via Stripe)
- ✅ Real file processing (Stirling PDF)

**Business Validation (Month 1-3):**
- 20-40 conversions/month
- 40-50% conversion rate (upload → pay)
- $20-40 revenue (validation phase)

---

## 📊 Current Status

### Completion: 85%

**✅ Completed (80% → 85%)**
- Core functionality: Upload → Compress → Pay → Download
- Stripe payment integration (test mode)
- Stirling PDF integration (Docker)
- Token-based download gating
- UX Phase 1 & 2: Spinner, error messages, compression levels, file size display
- Complete UX redesign for all 4 tools (compress, merge, split, convert)
- Tool page with service tabs + hero section
- Homepage structure analysis & documentation
- Homepage P1 Custom Template: Custom PHP template with all 7 visual enhancements
- Custom template CSS/JS loading fix

**🚧 In Progress**
- Homepage P1 Template: Testing & refinement
- Mobile optimization for custom template

**📋 Next Up**
1. **Homepage P1 Testing** (1-2h) - Verify template on live page, test responsive design
2. **Landing Page P2** (2h) - Mobile optimization, spacing audit
3. **Merge Tool** (3-4h) - Multi-file PDF merge refinement
4. **Split Tool** (3-4h) - Page extraction validation
5. **Production Prep** (2h) - Domain, SSL, monitoring

### Recent Work (Last 3 Days)

**Oct 21, 2025:**
- ✅ Fixed Homepage P1 template CSS/JS loading issue
- ✅ Moved asset enqueuing from template to functions.php
- ✅ Cleared WordPress cache

**Oct 20, 2025:**
- ✅ Complete UX redesign for all 4 tools (PR created)
- ✅ Tool page redesign with service tabs
- ✅ Homepage structure analysis (5 docs, 71KB)
- ✅ Homepage P1 Custom Template implementation

**Oct 15, 2025:**
- ✅ Elementor Phase 2 complete (PR #14)
- ✅ Landing Page P0 migration (PR #15)

### Open Items

**Current Branch:** feature/homepage-p1-custom-template
**Open PRs:** #26 - Homepage P1 Custom Template

**Known Issues:**
- Template CSS/JS loading - FIXED ✅
- Mobile responsive testing - IN PROGRESS

---

## 🗃️ Technical Architecture

### High-Level Flow

```
User Browser
    ↓ [Upload PDF]
WordPress (Frontend + Logic)
    ↓ [Process Request]
Stirling PDF (Docker :8080)
    ↓ [Compress/Merge/Split]
Result → Token Generation
    ↓ [Show Preview + Pay Button]
Stripe Payment ($0.99)
    ↓ [Mark Token as Paid]
Download (Server-Verified Token)
    ↓ [Auto-Delete After 1h]
```

### Tech Stack

**Frontend:**
- WordPress 6.x
- **Custom PHP Templates** (page-homepage-p1.php)
- Custom Theme: `pdfmaster-theme`
- Hardcoded HTML/CSS/JS (maximum control & performance)

**Backend:**
- Custom Plugins:
  - `pdfmaster-processor`: Upload, validation, Stirling API, token gating
  - `pdfmaster-payments`: Stripe integration, payment modal
- PHP 8.1+
- MySQL

**Processing:**
- Stirling PDF (Docker, self-hosted on :8080)
- Endpoint: `/api/v1/misc/compress-pdf`

**Payment:**
- Stripe (test mode)
- PaymentIntents API
- Amount: $0.99 (99 cents)

### File Structure

```
wp-content/
├── plugins/
│   ├── pdfmaster-processor/
│   │   ├── pdfmaster-processor.php
│   │   ├── includes/
│   │   │   ├── class-file-handler.php
│   │   │   ├── class-stirling-api.php
│   │   │   └── class-processor.php
│   │   └── assets/
│   │       ├── js/processor-scripts.js
│   │       └── css/processor-styles.css
│   │
│   └── pdfmaster-payments/
│       ├── pdfmaster-payments.php
│       ├── composer.json (Stripe PHP SDK)
│       ├── includes/
│       │   ├── class-stripe-handler.php
│       │   ├── class-payment-modal.php
│       │   └── admin/class-payments-admin.php
│       └── assets/
│           ├── js/payment-modal.js
│           └── css/payment-modal.css
│
└── themes/
    └── pdfmaster-theme/
        ├── style.css
        ├── functions.php (asset enqueuing, design tokens)
        ├── page-homepage-p1.php (custom template)
        └── assets/
            ├── css/
            │   ├── homepage-p1.css (15KB - all 7 enhancements)
            │   └── home-polish.css
            └── js/
                └── homepage-p1.js (2.4KB - interactions)
```

### Homepage P1 Template Architecture

**Approach:** Custom PHP Template (Zero Elementor Dependency)

**Decision Rationale:**
- User explicitly accepted **zero Elementor editability** for speed
- Hardcoded HTML/CSS/JS for maximum performance
- Complete control over all 7 visual enhancements
- Faster implementation vs. Elementor migration

**Files:**
1. `page-homepage-p1.php` (27KB)
   - Main WordPress page template
   - 8 sections: Sticky Trust Bar, Hero, Trust Badges, Tools, Pricing, Why It Works, FAQ, CTA
   - Uses WordPress template functions (get_header, get_footer, home_url)

2. `assets/css/homepage-p1.css` (15KB)
   - All 7 visual enhancements
   - Hardware-accelerated animations
   - Mobile-first responsive (1024px, 768px, 480px breakpoints)

3. `assets/js/homepage-p1.js` (2.4KB)
   - Sticky trust bar (requestAnimationFrame)
   - FAQ accordion
   - Smooth scrolling

**7 Visual Enhancements Implemented:**
1. Sticky trust bar (appears after 100px scroll)
2. Hero typography scale (56px H1 desktop, 36px mobile)
3. Enhanced tool card hovers (-8px lift, shadow-2xl, icon scale 1.1)
4. 2-column pricing comparison grid
5. FAQ accordion with smooth transitions
6. Gradient CTA with pulsing animation
7. Increased vertical spacing (80px between sections)

**Asset Enqueuing:**
- Function: `pdfm_homepage_p1_assets()` in functions.php:1097-1120
- Conditional loading: only when `is_page_template('page-homepage-p1.php')`
- Cache busting: version 1.0.1

### Payment Flow (Detailed)

1. **User uploads PDF** → processes via Stirling (free)
2. **Backend returns** `download_token` (unique, unpaid)
3. **Frontend shows** "Pay $0.99 to Download" button
4. **Payment modal opens** (Stripe Elements)
5. **PaymentIntent created** with `file_token` in metadata
6. **User pays** → Stripe confirms
7. **Backend marks token** as paid in database
8. **Download activates** → user downloads
9. **Server verifies token** before streaming file
10. **Auto-delete** after 1 hour (cron job)

### Security

- Token-based gating (server-side verification)
- No client-side bypass possible
- SSL encryption during upload/download
- Files automatically deleted after 1h
- No user accounts (no password leaks)

---

## 🛠️ Working Methodology

### Team Structure

**Claude Code (AI Assistant):** Full-Stack Developer & Strategist
- Business decisions & strategic guidance
- Code implementation
- Task planning & execution
- Git operations (commit, push, PR)
- Documentation updates
- Testing & validation

**User:** Product Owner
- Approvals & decisions
- Content strategy
- API keys provision
- Testing & feedback

### Workflow: Research → Plan → Code → Test → Deploy

```
1. RESEARCH
   - Read context (files, docs, previous sessions)
   - Gather requirements
   - Understand current state

2. PLAN
   - Create task specification
   - Break down into steps
   - Identify affected files

3. CODE
   - Implement solution
   - Follow WordPress best practices
   - Maintain code standards

4. TEST
   - Verify functionality
   - Test E2E flow
   - Check responsive design

5. DEPLOY
   - Git commit with descriptive message
   - Push to branch
   - Create PR
   - Update documentation
```

### Development Approach Decision Tree

**Custom PHP Template vs. Elementor**

Use **Custom PHP Template** when:
- User explicitly accepts zero Elementor editability
- Speed is critical priority
- Complex visual enhancements (7+ features)
- Performance optimization needed
- Complete control over HTML/CSS/JS required

Use **Elementor** when:
- User needs self-service editing
- Content updates are frequent
- Visual changes by non-developers
- Standard layouts and widgets sufficient
- Editor=Front parity is required

**Homepage P1 Decision:** Custom PHP Template
- Reason: User accepted zero editability for speed
- Benefit: Faster implementation, maximum control
- Trade-off: Content changes require code updates

### Code Standards

**WordPress Conventions:**
- Prefix all functions: `pdfm_*`
- Prefix all CSS classes: `.pdfm-*`
- Use WordPress template functions (get_header, get_footer, home_url)
- Enqueue assets properly via wp_enqueue_scripts hook
- Type hints for PHP 8.1+ (`: void`, `: string`, `: array`)

**CSS Best Practices:**
- Mobile-first responsive design
- Hardware-accelerated animations (transform, opacity)
- CSS custom properties for design tokens
- Consistent breakpoints (1024px, 768px, 480px)
- BEM-inspired naming where appropriate

**JavaScript Standards:**
- jQuery for DOM manipulation (WordPress standard)
- requestAnimationFrame for scroll handlers
- Event delegation where possible
- Defensive checks for element existence

### Git Workflow

**Branch Naming:**
- `feature/[task-name]` - New features
- `fix/[issue-name]` - Bug fixes
- `docs/[doc-name]` - Documentation updates

**Commit Messages:**
```
feat: Add Homepage P1 custom template with 7 enhancements
fix: CSS loading issue in custom template
docs: Update project documentation for Claude Code workflow
```

**PR Process:**
1. Create feature branch
2. Implement & test
3. Commit with descriptive message
4. Push to origin
5. Create PR with context
6. User reviews & merges

### Documentation Protocol

**Every Session:**

**BEFORE:**
- Read PROJECT_STATUS.md
- Read last session notes
- Check open PRs
- Confirm current priority

**DURING:**
- Update files as needed
- Test changes thoroughly
- Document decisions inline

**AFTER:**
- Update PROJECT_STATUS.md
- Create session notes if significant work
- Push documentation changes
- Update this file if workflow changes

**File Updates:**
- PROJECT_STATUS.md: Current state, recent work, next tasks
- PDFMASTER_PROJECT_DOCS.md: Methodology, architecture, reference
- Session notes: Detailed decisions and context

---

## 🚀 Getting Started

### Local Environment

**URLs:**
- Site: http://localhost:10003/
- WP Admin: http://localhost:10003/wp-admin
- Test Page: http://localhost:10003/test-processor/
- Stirling PDF: http://localhost:8080

**Credentials:**
- WP Admin: [as configured in Local]
- Stripe: Test mode (keys in WP Admin → Settings → PDFMaster Payments)

### Quick Health Check

```bash
# WordPress
open http://localhost:10003/wp-admin

# Stirling PDF
curl http://localhost:8080/api/v1/general/health

# Docker
docker ps | grep stirling
```

### E2E Test Flow (Processor)

1. Open: http://localhost:10003/test-processor/
2. Upload PDF file
3. Click "Compress PDF"
4. See processing spinner
5. Click "Pay $0.99"
6. Enter test card: `4242 4242 4242 4242`
7. Confirm payment
8. Download processed file
9. Verify compression worked

### Test Homepage P1 Template

1. WP Admin → Pages → Add New
2. Page Title: "Homepage P1 Test"
3. Page Attributes → Template → "Homepage P1 Template"
4. Publish
5. View page
6. Verify all 7 enhancements:
   - Sticky trust bar (scroll down 100px)
   - Large hero typography (56px H1)
   - Tool card hover effects (hover over cards)
   - 2-column pricing comparison
   - FAQ accordion (click questions)
   - Pulsing CTA button
   - Generous spacing (80px)
7. Test mobile (resize browser or use DevTools)

### Common Commands

**WordPress (WP-CLI):**
```bash
cd ~/Local\ Sites/pdfmaster/app/public

# Plugin management
wp plugin list
wp plugin activate pdfmaster-processor

# Cache
wp cache flush

# Debug log
tail -f wp-content/debug.log
```

**Docker (Stirling PDF):**
```bash
# Check status
docker ps | grep stirling

# Restart
docker restart stirling-pdf

# Logs
docker logs stirling-pdf --tail 50
```

**Git:**
```bash
# Current work
git status
git branch

# Recent history
git log --oneline -10

# Sync
git pull origin main
git push origin feature/branch-name
```

---

## 📋 Next Steps

### Immediate Priorities (Launch Path)

**Week 1: Homepage P1 Completion (2-3h)**
1. Test Homepage P1 template on live page (1h)
2. Mobile responsive testing & fixes (1h)
3. Deploy to staging (1h)

**Week 2: Feature Expansion (6-8h)**
4. Merge Tool refinement (3-4h) - Multi-file handling
5. Split Tool validation (3-4h) - Page range checks

**Week 3: Production Prep (2-4h)**
6. Production environment setup
7. Domain + SSL configuration
8. Monitoring & error tracking
9. Performance optimization

### Future Enhancements (Post-Launch)

**Phase 2: Additional Tools**
- Convert to PDF (Word, Excel, PowerPoint, images)
- Rotate pages
- Add watermark
- PDF security (password protection)

**Phase 3: Business Growth**
- SEO optimization
- Content marketing
- Affiliate program
- API access for power users

**Phase 4: Scale**
- Credit bundles ($9.90 for 12, $24.90 for 36)
- Volume discounts for frequent users
- White-label option for enterprises

---

## 📖 Quick Reference

### Important Links

- **Repository:** https://github.com/zurychhh/pdfmaster-wp
- **Stripe Dashboard:** https://dashboard.stripe.com/test/payments
- **Stirling Swagger:** http://localhost:8080/swagger-ui/index.html

### Documentation Structure

```
ROOT
├── README.md (quick start)
├── PROJECT_STATUS.md (current state) ← UPDATE EACH SESSION
└── docs/
    ├── PDFMASTER_PROJECT_DOCS.md (this file - methodology & reference)
    ├── user/
    │   ├── ELEMENTOR_EDITING_GUIDE.md (legacy - for Elementor pages)
    │   └── ELEMENTOR_STRUCTURE_MAP.md (legacy - for Elementor pages)
    └── session_notes/
        └── SESSION_NOTES_[date].md (work logs)
```

### Stripe Test Cards

- **Success:** 4242 4242 4242 4242
- **Decline:** 4000 0000 0000 0002
- **3D Secure:** 4000 0027 6000 3184

### Stirling PDF Endpoints

**Compress:**
- URL: `http://localhost:8080/api/v1/misc/compress-pdf`
- Method: POST multipart/form-data
- Fields:
  - `fileInput`: PDF file
  - `optimizeLevel`: 5 (1-9, lower=more compression)
  - `expectedOutputSize`: 25KB
  - `linearize`: false
  - `normalize`: false
  - `grayscale`: false

### WordPress Template System

**Creating Custom Page Templates:**

1. Create file: `wp-content/themes/pdfmaster-theme/page-[template-name].php`
2. Add template header:
   ```php
   <?php
   /**
    * Template Name: Template Display Name
    */
   ```
3. Use WordPress functions:
   - `get_header()` - Load header.php
   - `get_footer()` - Load footer.php
   - `home_url()` - Get site URL
   - `get_template_directory_uri()` - Get theme URL
4. Enqueue assets in functions.php:
   ```php
   function prefix_template_assets() {
       if (is_page_template('page-template-name.php')) {
           wp_enqueue_style('name', get_template_directory_uri() . '/path.css');
           wp_enqueue_script('name', get_template_directory_uri() . '/path.js', array('jquery'));
       }
   }
   add_action('wp_enqueue_scripts', 'prefix_template_assets');
   ```
5. Select template: WP Admin → Page → Template dropdown

### Troubleshooting

**CSS/JS not loading on custom template:**
- ✅ **FIXED:** Asset enqueuing must be in functions.php, not template file
- Check: `wp_enqueue_scripts` hook fires before template renders
- Verify: `is_page_template('page-name.php')` conditional works
- Clear cache: `wp cache flush`
- Hard refresh browser: Cmd/Ctrl+Shift+R

**Payment modal doesn't open:**
- Check Stripe.js loaded in HEAD
- Verify API keys in WP Admin
- Check browser console for errors

**Stirling PDF 404:**
- Verify Docker running: `docker ps | grep stirling`
- Check endpoint: `curl http://localhost:8080/api/v1/general/health`
- Restart if needed: `docker restart stirling-pdf`

**File not downloading:**
- Verify token paid in database
- Check download endpoint logs
- Confirm file exists in temp storage

---

## 📝 Reusable Patterns

### Token-Based Download Gating

**Pattern:** Process First, Pay to Download

**Use Cases:**
- Image tools (resize, convert, compress)
- Document converters
- Audio/video processing
- Data analysis tools

**Implementation:**
1. User uploads → processing happens FREE
2. Backend generates unique `download_token` (unpaid)
3. Frontend shows "Pay $X to Download"
4. Payment includes `file_token` in metadata
5. Payment success → Backend marks token as PAID
6. Download endpoint verifies token before streaming

### WordPress + External Docker Service

**Pattern:** WP as Orchestrator + Heavy Processing Offloaded

**Architecture:**
```
WordPress (Frontend + Logic)
    ↓
External Service (Docker)
    ↓
File Processing (CPU intensive)
    ↓
Result → WordPress → User
```

**Benefits:**
- WordPress handles UI, payments, user flow (easy to build)
- Heavy processing isolated in Docker (scalable, replaceable)
- Clean separation of concerns

### Custom WordPress Template Pattern

**Pattern:** Hardcoded PHP Template for Performance

**When to Use:**
- User accepts zero visual editability
- Complex custom layouts (7+ features)
- Performance critical pages
- Maximum control over HTML/CSS/JS

**Implementation:**
```php
<?php
/**
 * Template Name: Custom Template
 */

get_header();
?>

<div class="custom-template-wrapper">
    <!-- Hardcoded HTML here -->
    <section class="hero">
        <h1>Custom Content</h1>
    </section>
</div>

<?php get_footer(); ?>
```

**Asset Loading (functions.php):**
```php
function prefix_custom_assets(): void {
    if (is_page_template('page-custom.php')) {
        wp_enqueue_style('custom-css',
            get_template_directory_uri() . '/assets/css/custom.css',
            array(), '1.0.0');

        wp_enqueue_script('custom-js',
            get_template_directory_uri() . '/assets/js/custom.js',
            array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'prefix_custom_assets');
```

---

## 🎓 Best Practices

### Development

- **Read before answering:** Check files, don't speculate
- **Incremental changes:** Small commits, test each step
- **Documentation as code:** Update docs as part of every task
- **Simple > complex:** For MVP, avoid over-engineering

### WordPress

- **WP_DEBUG ON:** During development (off in production)
- **Test activation:** No fatal errors on plugin activate
- **Proper enqueuing:** Use wp_enqueue_scripts hook, not inline
- **Cache clearing:** After every template/CSS change
- **Template hierarchy:** Use WordPress standard (page-*.php)

### Custom Templates

- **Asset enqueuing:** Always in functions.php, never in template
- **WordPress functions:** Use get_header(), get_footer(), home_url()
- **Conditional loading:** Use is_page_template() to avoid conflicts
- **Version control:** Bump version on CSS/JS changes for cache busting
- **Fallback:** Provide mobile breakpoints (1024px, 768px, 480px)

### CSS/JavaScript

- **Mobile-first:** Start with mobile styles, add desktop enhancements
- **Performance:** Use hardware-accelerated properties (transform, opacity)
- **requestAnimationFrame:** For scroll handlers and animations
- **Progressive enhancement:** Works without JS, enhanced with JS
- **Defensive coding:** Check element existence before manipulation

### Git & Documentation

- **Descriptive commits:** feat/fix/docs prefix with clear message
- **Feature branches:** One branch per task
- **Update PROJECT_STATUS:** Every session
- **Session notes:** For significant decisions or complex work
- **Keep PDFMASTER_PROJECT_DOCS current:** Update when methodology changes

---

## ❓ FAQ

### Business

**Q: Why $0.99 instead of subscription?**
A: Most users process PDFs 2-5 times/month, not daily. $0.99/use = $9.90/year vs $120-240 for annual subscriptions. Easier conversion (no commitment).

**Q: What's the target conversion rate?**
A: 40-50% (upload → pay). Commitment bias helps: user already invested time uploading/processing.

**Q: When do we add more tools?**
A: After MVP validation (20-40 conversions in Month 1). Then Merge → Split → Convert.

### Technical

**Q: Why WordPress instead of React SPA?**
A: Faster MVP iteration. WordPress ecosystem for payments/hosting. Focus on business validation, not tech perfection.

**Q: Why self-hosted Stirling PDF?**
A: No per-operation API costs. Better margins. Full control over processing. Better privacy (files stay on our servers).

**Q: Why custom PHP template instead of Elementor for Homepage P1?**
A: User explicitly accepted zero editability for speed. Custom template = faster implementation, complete control over all 7 visual enhancements, better performance.

**Q: How do we handle scale?**
A: Docker makes it easy to add more Stirling instances. WordPress scales to millions of pageviews. Start simple, optimize when needed.

### Workflow

**Q: When to use custom PHP template vs Elementor?**
A: Custom template when: speed critical, complex features, user accepts zero editability. Elementor when: frequent content updates, non-developer editing, standard layouts.

**Q: How to handle template CSS not loading?**
A: Asset enqueuing MUST be in functions.php with wp_enqueue_scripts hook. Never place enqueuing code inside template file after get_footer().

**Q: What if changes don't appear?**
A: 1) Clear WP cache: `wp cache flush` 2) Hard refresh browser: Cmd/Ctrl+Shift+R 3) Check version number (bump for cache bust)

---

## 📌 Change Log

**2025-10-21 (v3.0):**
- **MAJOR UPDATE:** Factory.ai → Claude Code workflow
- Custom PHP template approach for Homepage P1
- Fixed CSS/JS loading issue (enqueuing in functions.php)
- Updated all references from "Droid" to "Claude Code"
- Documented custom template pattern and best practices
- Added troubleshooting for template asset loading
- Status: 80% → 85% complete

**2025-10-20 (v2.5):**
- Complete UX redesign for all 4 tools
- Homepage structure analysis (5 docs, 71KB)
- Tool page redesign with service tabs

**2025-10-15 (v2.0):**
- Unified all project documentation into single file
- Elementor Phase 2 completion
- Landing Page P0 migration
- Status: 65% → 80% complete

**2025-10-10 (v1.0):**
- Initial PROJECT_STATUS.md
- Payment model change ($2.90 → $0.99)
- UX Phase 2 completion
- Basic documentation structure

---

## 🚦 Status Indicators

**🟢 Working Well:**
- Core upload → compress → pay → download flow
- Stripe integration
- Stirling PDF processing
- Custom template implementation
- Documentation structure
- Git workflow

**🟡 In Progress:**
- Homepage P1 template testing
- Mobile optimization
- Custom template refinement

**🔴 Needs Attention:**
- None currently

---

## 📞 For Next Session

**Opening Checklist:**

1. Read PDFMASTER_PROJECT_DOCS.md (this file)
2. Read PROJECT_STATUS.md (current state)
3. Check open PRs and branches
4. Review last session notes (if any)
5. Verify local environment (WordPress, Stirling, Docker)

**Standard Opening Message:**

```
Hi! Continuing work on PDFMaster.

I've read:
- PDFMASTER_PROJECT_DOCS.md (methodology, architecture)
- PROJECT_STATUS.md (current state, recent work)
- Open PRs: [list or "none"]

Current priority: [task from Next Steps]

Environment check:
- WordPress: [URL]
- Stirling PDF: [status]
- Git branch: [current]

Ready to proceed. Confirm?
```

**Before Session End:**
- Update PROJECT_STATUS.md with new work
- Create session notes if significant decisions
- Push changes to GitHub
- Update this file if methodology changes

---

**End of Unified Documentation**

*Version 3.0 - Claude Code Workflow*

*This file serves as:*
- Comprehensive project reference
- Methodology documentation
- Technical architecture guide
- Best practices handbook
- Troubleshooting resource

*Keep updated:*
- PROJECT_STATUS.md - current state (update every session)
- PDFMASTER_PROJECT_DOCS.md - methodology (update when workflow changes)
- Session notes - detailed context (create when needed)
