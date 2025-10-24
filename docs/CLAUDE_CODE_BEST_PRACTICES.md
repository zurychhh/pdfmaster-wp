# Claude Code Best Practices Guide
**Bazując na projekcie: PDFMaster**
**Utworzony:** 2025-10-24
**Wersja:** 1.0

---

## 📘 O tym dokumencie

Ten przewodnik powstał na bazie rzeczywistego doświadczenia z projektu **PDFMaster** - od MVP do production deployment. Zawiera sprawdzone patterns, real debugging cases, i praktyczne przykłady komunikacji z Claude Code (AI development assistant).

**Kontekst projektu:**
- WordPress pay-per-action PDF app
- Stack: WordPress + Elementor + Stripe + Stirling PDF + Railway + Vercel
- 15+ sesji development (2025-10-15 → 2025-10-24)
- 3 major emergencies (redirect loop, CORS, Stripe Live mode)
- Production deployment: https://www.pdfspark.app

**Dla kogo:**
- Developerzy pracujący z Claude Code
- Product ownery koordynujący AI development
- Każdy kto chce maksymalizować produktywność z AI assistants

---

## 🎯 Fundamentalne Zasady

### 1. Claude Code Jest End-to-End Executor

**✅ DOBRZE:**
```
User: "Mamy redirect loop na www.pdfspark.app (ERR_TOO_MANY_REDIRECTS).
Fix it."
```

**❌ ŹLE:**
```
User: "Co myślisz o redirect loop?"
User: "Może sprawdź wp-config?"
User: "A co z proxy headers?"
User: "Ok to może spróbuj..."
```

**Dlaczego:**
- Claude Code działa najlepiej gdy może wykonać całe zadanie autonomicznie
- Micro-management spowalnia proces i wprowadza błędy
- Daj context, zatwierdź approach, pozwól wykonać

**Real Case z projektu:**
```
Session 2025-10-24:
User: "ERR_TOO_MANY_REDIRECTS na www.pdfspark.app"
Claude: [diagnostyka → identyfikacja root cause → fix wp-config.php →
         create MU plugin → test → verify] (wykonane w 10 minut)
```

---

### 2. Context Is King

**✅ DOBRZE - Pełny Context:**
```
User: "Stripe Live mode enabled w WordPress admin, ale płatności failują
z errorem 'Request was made in test mode'.

Database pokazuje:
- mode: 'live'
- live_publishable_key: pk_live_...
- live_secret_key: sk_live_...

Frontend używa test card (4242...) ale to powinno failować z innym errorem.
Create diagnostic report with root cause analysis."
```

**❌ ŹLE - Brak Contextu:**
```
User: "Stripe nie działa"
```

**Elementy Dobrego Contextu:**
1. **Co się dzieje** (symptom)
2. **Czego oczekujesz** (expected behavior)
3. **Co już sprawdziłeś** (eliminacja)
4. **Relevant data** (error messages, logs, config)
5. **Desired outcome** (fix vs investigate vs document)

**Real Case z projektu:**
```
Session 2025-10-24 - Stripe Live Mode Diagnostic:

User podał:
✅ Symptom: "test mode" error
✅ Current state: Live mode enabled in admin
✅ Data: Database values, expected keys
✅ Outcome: "Create diagnostic report"

Claude wykonał:
→ Database analysis (wp_options table)
→ Frontend key loading trace (payment-modal.php)
→ Backend filter priority analysis (pdfm-railway-config.php)
→ ROOT CAUSE: Railway env vars override with pk_test_
→ Solution: Update Railway env vars to pk_live_
→ 15-page diagnostic report w/ filter flow diagram
```

---

### 3. Trust but Verify

**Pattern:**
1. Claude wykonuje task
2. Claude raportuje co zrobił + verification
3. User testuje critical path
4. User potwierdza lub zgłasza issue

**✅ DOBRZE:**
```
Claude: [fixes redirect loop, commits, pushes]
User: [tests site manually] "cos poszlo nie tak" [konkretny symptom]
Claude: [deeper diagnostic, identifies secondary issue, fixes]
User: "dziala" ✅
```

**❌ ŹLE:**
```
Claude: [fixes issue]
User: "ok" [nie testuje]
[problem pojawia się 2 dni później, context lost]
```

**Verification Checklist (z projektu):**
- [ ] Site loads (200 HTTP response)
- [ ] No console errors
- [ ] Payment flow works end-to-end
- [ ] Database state correct
- [ ] Railway deployment successful
- [ ] Stripe Dashboard shows expected mode

---

## 💬 Komunikacja Patterns

### Pattern 1: Emergency Fix

**Kiedy:** Production down, błąd krytyczny

**Template:**
```
🚨 EMERGENCY: [symptom]

What's broken: [specific behavior]
Error message: [exact error text]
User impact: [who/what affected]
When started: [timestamp if known]

[Optional: What I already tried]

Fix it ASAP.
```

**Real Example:**
```
User: "ERR_TOO_MANY_REDIRECTS na www.pdfspark.app"
→ Claude wykonał emergency fix w 10 minut
```

---

### Pattern 2: Feature Request

**Kiedy:** Nowa funkcjonalność

**Template:**
```
Feature: [nazwa]

What: [opis funkcjonalności]
Why: [business reason / user benefit]
Acceptance criteria:
- [ ] Criterion 1
- [ ] Criterion 2
- [ ] Criterion 3

[Optional: Technical constraints]
[Optional: Design mockups / references]

Ready when you are.
```

**Real Example:**
```
Session 2025-10-20:
User: "Complete UX redesign for compress tool:
- Animated success state with checkmark
- Stats card (Original → Compressed → % saved)
- Professional payment modal (mockup-based)
- Post-payment download screen

Apply to all 4 tools. Mobile responsive."

→ Claude wykonał 3 commits (6d44e2e, 0853d4a, 82d3622)
→ +1,025 lines code
→ E2E tested all tools
```

---

### Pattern 3: Debugging Request

**Kiedy:** Coś nie działa, potrzebujesz analizy

**Template:**
```
Debug: [problem description]

Expected: [co powinno się stać]
Actual: [co się dzieje]

Environment:
- Production / Local / Staging
- URL: [if applicable]
- Browser: [if frontend issue]

Data points:
- [Error messages]
- [Config values]
- [Recent changes]

Deliverable: [diagnostic report / fix / explanation]
```

**Real Example:**
```
User: "Stripe Live mode enabled w admin but payments fail with
'test mode' error. Create diagnostic report."

→ Claude stworzył 15-page report z:
   - Database analysis
   - Frontend/backend trace
   - Filter priority flow diagram
   - Root cause (Railway env vars)
   - Solution with verification steps
```

---

### Pattern 4: Documentation Update

**Kiedy:** Po major milestone, deploy, lub sesji

**Template:**
```
Update all project .md files based on today's work.

Include:
- Technical changes (what changed, why, how)
- Business updates (metrics, status, milestones)
- Lessons learned
- Next steps

Return: List of updated files + locations
```

**Real Example:**
```
Session 2025-10-24:
User: "prosze cie zebys zrobil update wszystkich plikow projektowych .md
zgodnie z tym jak to robilismy wczesniej. Zarowno czesc techniczna
jak i biznesowa"

→ Claude zaktualizował:
   - README.md (+227 lines)
   - PROJECT_STATUS.md (header + status)
   - SESSION_NOTES_2025-10-24.md (~800 lines, NOWY)
   - DOCUMENTATION_UPDATE_REPORT.md (NOWY)

Total: 1,357 lines dokumentacji
```

---

## 🐛 Real Debugging Cases

### Case 1: Redirect Loop (ERR_TOO_MANY_REDIRECTS)

**Problem:**
- Site down, infinite redirect
- Symptom: ERR_TOO_MANY_REDIRECTS

**Initial Attempt (Failed):**
```php
// wp-config.php - PROBLEM
// Usunięto WP_HOME/WP_SITEURL constants
// WordPress czytał database ale konfliktował z proxy headers
```

**Root Cause Found:**
- WordPress trusted X-Forwarded-Host from Railway proxy
- Proxy wysyłał `railway.app` domain
- WordPress redirected to match database (`pdfspark.app`)
- Loop: pdfspark.app ↔ railway.app

**Solution:**
```php
// wp-config.php - FIXED
// FORCE domain instead of trusting proxy
if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'www.pdfspark.app';  // FORCE
}

define('WP_HOME', 'https://www.pdfspark.app');
define('WP_SITEURL', 'https://www.pdfspark.app');
```

**Lesson:**
> Don't blindly trust proxy headers. Force specific domain when using proxies.

---

### Case 2: CORS Error

**Problem:**
```
Cross-Origin Request Blocked: www.pdfspark.app → pdfspark.app/wp-admin/admin-ajax.php
```

**Diagnosis:**
- User accessed: `www.pdfspark.app`
- WordPress URLs: `pdfspark.app` (no www)
- Browser blocked: different origins

**Root Cause:**
- Database: `home = https://pdfspark.app`
- Access URL: `https://www.pdfspark.app`
- Same-origin policy violation

**Solution:**
```sql
-- Database fix
UPDATE wp_options
SET option_value = 'https://www.pdfspark.app'
WHERE option_name IN ('siteurl', 'home');
```

```php
// wp-config.php
define('WP_HOME', 'https://www.pdfspark.app');
define('WP_SITEURL', 'https://www.pdfspark.app');
```

```php
// force-domain.php MU plugin (safety net)
add_filter('option_home', fn($url) => 'https://www.pdfspark.app');
```

**Lesson:**
> Domain consistency is critical. www.example.com ≠ example.com for CORS.

---

### Case 3: Stripe Live Mode Using Test Keys

**Problem:**
- WordPress admin: "Live Mode" enabled ✅
- Database: `mode='live'`, `live_publishable_key=pk_live_...` ✅
- Payments: "Request made in test mode" error ❌

**Diagnosis Process:**

**Step 1: Database Check**
```sql
SELECT option_value FROM wp_options WHERE option_name='pdfm_stripe_settings';
→ mode: 'live' ✅
→ live_publishable_key: pk_live_... ✅
```

**Step 2: Frontend Trace**
```javascript
// payment-modal.js
console.log(pdfmPayments.publishableKey);
→ Output: pk_test_... ❌ (should be pk_live_)
```

**Step 3: Backend Filter Analysis**
```php
// Filter execution order:
1. PaymentsAdmin::filter_publishable_key() [priority 10]
   → Returns: pk_live_... (from database)

2. pdfm-railway-config.php filter [priority 20]
   → getenv('STRIPE_PUBLISHABLE_KEY')
   → Returns: pk_test_... (Railway env var)
   → OVERRIDES database value ❌
```

**Root Cause:**
- Railway environment variables contained TEST keys
- MU plugin (`pdfm-railway-config.php`) runs at priority 20
- Higher priority (20) overrides lower (10)
- ENV VAR wins over DATABASE

**Solution:**
```bash
# Update Railway env vars to LIVE keys
railway variables --service pdfmaster-wp \
  --set "STRIPE_PUBLISHABLE_KEY=pk_live_..."

railway variables --service pdfmaster-wp \
  --set "STRIPE_SECRET_KEY=sk_live_..."
```

**Lesson:**
> Filter priority matters. Higher number = later execution = final override.
> Environment variables can silently override database config.

---

### Case 4: Admin Navigation Broken

**Problem:**
- Clicking admin links → redirects to frontend
- Shows "Elementor templates" message

**Diagnosis:**
- Same root cause as CORS error
- Database URLs incorrect

**Solution:**
- Fixed by CORS fix (same solution)

**Lesson:**
> Multiple symptoms can share one root cause. Fix root, not symptoms.

---

## 🔧 Technical Best Practices

### 1. Git & Deployment Workflow

**Pattern that works:**
```bash
# 1. Work happens (Claude makes changes)
git status
git diff

# 2. Commit with descriptive message
git commit -m "fix: resolve redirect loop by forcing domain in wp-config

Root cause: WordPress trusted X-Forwarded-Host from Railway proxy
causing infinite redirect between pdfspark.app ↔ railway.app.

Solution: Force $_SERVER['HTTP_HOST'] = 'www.pdfspark.app'
and set WP_HOME/WP_SITEURL constants explicitly.

Files:
- wp-config.php (domain forcing)
- wp-content/mu-plugins/force-domain.php (safety net)

Tested: Site loads, no redirect loop, admin works.

🤖 Generated with Claude Code
Co-Authored-By: Claude <noreply@anthropic.com>"

# 3. Push (triggers Railway auto-deploy)
git push origin main

# 4. Verify deployment
railway status
curl -I https://www.pdfspark.app

# 5. Monitor logs if issues
railway logs --tail 50 --follow
```

**Commit Message Template:**
```
<type>: <short summary>

<root cause explanation>

<solution description>

Files:
- file1.php (what changed)
- file2.js (what changed)

Tested: <verification steps>

🤖 Generated with Claude Code
Co-Authored-By: Claude <noreply@anthropic.com>
```

**Types:**
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation
- `refactor:` - Code refactor (no behavior change)
- `test:` - Tests
- `chore:` - Build, config, etc.

---

### 2. Documentation Standards

**Update Documentation Every Session:**

**Files to Update:**
1. **PROJECT_STATUS.md** - Current state, recent work, next tasks
2. **README.md** - Quick start, links, major updates
3. **SESSION_NOTES_YYYY-MM-DD.md** - Detailed session log
4. **Diagnostic Reports** - For complex debugging (save to `/tmp/`)

**Documentation Template (Session Notes):**
```markdown
# Session Notes - YYYY-MM-DD

## Executive Summary
- What was accomplished (3-5 bullets)
- Business impact

## Problems Fixed
### Problem 1: [Name]
- **Symptom:** [what user saw]
- **Root Cause:** [technical reason]
- **Solution:** [what was changed]
- **Files Modified:** [list]
- **Commit:** [hash]

## New Features Added
### Feature 1: [Name]
- **Description:** [what it does]
- **Files:** [list]
- **Testing:** [how verified]

## Lessons Learned
1. [Lesson] - [context]
2. [Lesson] - [context]

## What's Next
- Immediate: [tasks]
- Short-term: [1-2 weeks]
- Medium-term: [1 month]
```

---

### 3. Environment Configuration

**Critical Pattern:**

```
Database Config (WordPress)
     ↓ [can be overridden by]
Environment Variables (Railway)
     ↓ [can be overridden by]
Must-Use Plugins (wp-content/mu-plugins/)
     ↓ [can be overridden by]
wp-config.php Constants
```

**Priority Order (lowest to highest):**
1. Database (wp_options table)
2. Regular Plugins
3. Must-Use Plugins
4. wp-config.php constants
5. Environment variables (if accessed directly)

**Example - Stripe Keys:**
```php
// DATABASE: wp_options
mode: 'live'
live_publishable_key: 'pk_live_...'

// ENVIRONMENT VARIABLE (Railway)
STRIPE_PUBLISHABLE_KEY=pk_test_...  // ← THIS WINS (if MU plugin uses it)

// MU PLUGIN FILTER (priority 20)
add_filter('pdfm_stripe_publishable_key', function($value) {
    $env_key = getenv('STRIPE_PUBLISHABLE_KEY');  // Gets pk_test_
    return $env_key ?: $value;  // Returns pk_test_ (overrides database)
}, 20);
```

**Best Practice:**
> Document your override hierarchy. Know which source has final say.

---

### 4. Debugging Tools

**Essential Commands:**

```bash
# 1. Check site status
curl -I https://www.pdfspark.app

# 2. WordPress debug log
tail -f wp-content/debug.log

# 3. Railway logs
railway logs --tail 100 --follow

# 4. Database queries
mysql -h interchange.proxy.rlwy.net -P 22656 -u root -p railway
# Then:
SELECT option_name, option_value
FROM wp_options
WHERE option_name IN ('siteurl', 'home');

# 5. Check environment variables
railway variables --service pdfmaster-wp

# 6. Git history
git log --oneline -10
git log --grep="Stripe" --oneline

# 7. Check plugin status
wp plugin list

# 8. Check Stripe settings
wp option get pdfm_stripe_settings
```

**Browser Console (Frontend Debug):**
```javascript
// Check Stripe key
console.log(pdfmPayments.publishableKey);

// Check mode
console.log(pdfmPayments.mode);

// Check if Stripe loaded
console.log(typeof Stripe);
```

---

### 5. Testing Protocol

**E2E Test (All 4 Tools):**

```
1. Navigate to: https://www.pdfspark.app/services
2. Select tool: Compress PDF
3. Upload valid PDF (>1MB)
4. Click "Process PDF"
5. Wait for success state ✅
6. Verify stats card (Original → Compressed → % saved)
7. Click "Pay $0.99"
8. Payment modal opens ✅
9. Enter card: 4242 4242 4242 4242, any future date, any CVC
10. Click "Pay Now"
11. Wait for success ✅
12. Verify download button appears
13. Click "Download Your PDF"
14. File downloads ✅
15. Open file → verify not corrupted ✅

Repeat for: Merge, Split, Convert
```

**Production Payment Test (Live Mode):**
```
1. Use REAL credit card (not 4242...)
2. Process file
3. Complete payment ($0.99 charged)
4. Check Stripe Dashboard (Live mode) → transaction visible ✅
5. Download file
6. [Optional] Refund in Stripe Dashboard
```

---

## 🚫 Anti-Patterns (Co Unikać)

### Anti-Pattern 1: Micro-Management

**❌ ŹLE:**
```
User: "Check wp-config.php line 44"
Claude: [checks]
User: "Now check if X-Forwarded-Host is set"
Claude: [checks]
User: "Ok now try changing it to..."
Claude: [changes]
User: "Did it work?"
Claude: [tests]
```

**✅ DOBRZE:**
```
User: "Redirect loop na www.pdfspark.app. Fix it."
Claude: [full diagnostic → identifies root cause → implements solution
        → creates backup MU plugin → tests → verifies → commits]
```

---

### Anti-Pattern 2: Vague Requests

**❌ ŹLE:**
```
User: "Stripe doesn't work"
User: "Fix the site"
User: "Something's broken"
```

**✅ DOBRZE:**
```
User: "Stripe Live mode enabled in admin but payments fail with error:
'Request was made in test mode, but used a non-test card'.

Database shows mode='live' with live keys populated.
Create diagnostic report with root cause analysis."
```

---

### Anti-Pattern 3: No Testing After Changes

**❌ ŹLE:**
```
Claude: [fixes critical issue, commits, pushes]
User: "ok thanks" [doesn't test]
[2 days later]
User: "Site is broken"
```

**✅ DOBRZE:**
```
Claude: [fixes issue]
User: [tests E2E flow manually]
User: "Tested - upload, payment, download all work. Deployed to prod ✅"
```

---

### Anti-Pattern 4: Skipping Documentation

**❌ ŹLE:**
```
[Major feature shipped]
[Production deployment]
[No documentation update]
[3 weeks later - context lost]
```

**✅ DOBRZE:**
```
[Major feature shipped]
User: "Update all .md files with today's work - technical + business"
Claude: [updates README, PROJECT_STATUS, creates session notes]
User: [reviews, approves]
→ Complete record maintained ✅
```

---

### Anti-Pattern 5: Ignoring Root Cause

**❌ ŹLE:**
```
Symptom: CORS error
Fix: Add CORS headers

[Next day]
Symptom: Admin navigation broken
Fix: Add redirect rule

[Next day]
Symptom: Payment modal CORS error
Fix: Add another CORS header

→ 3 band-aid fixes for 1 root problem
```

**✅ DOBRZE:**
```
Symptoms: CORS error + admin broken + payment issues
Root Cause: Database URLs (pdfspark.app) ≠ Access URL (www.pdfspark.app)
Fix: Update database URLs to www.pdfspark.app

→ All 3 symptoms resolved with 1 root fix ✅
```

---

## 📋 Templates & Checklists

### Template 1: Session Start

```
Hi! Continuing work on [PROJECT_NAME].

I've read:
- PROJECT_STATUS.md (current state)
- [Recent session notes if applicable]

Current environment:
- Production: [URL]
- Local: [URL]
- Last deploy: [timestamp or "unknown"]

Next task: [describe what you want to work on]

[Optional: Context about what changed since last session]

Ready to proceed.
```

---

### Template 2: Emergency Escalation

```
🚨 EMERGENCY - [PROBLEM]

Severity: [Critical / High / Medium]
Impact: [Production down / Feature broken / UX degraded]
Started: [timestamp or "just now"]

Symptom:
[Exact error message or behavior]

Environment:
- URL: [if applicable]
- Browser: [if frontend]
- User actions: [what triggers it]

What I tried:
- [Action 1] → [Result]
- [Action 2] → [Result]

[Optional: Logs / screenshots / error traces]

ASAP fix needed.
```

---

### Template 3: Feature Handoff

```
Feature Request: [NAME]

Business Context:
[Why we need this, user benefit, business goal]

Requirements:
- [ ] Requirement 1 (acceptance criterion)
- [ ] Requirement 2 (acceptance criterion)
- [ ] Requirement 3 (acceptance criterion)

Technical Constraints:
- [Constraint 1 if applicable]
- [Constraint 2 if applicable]

Design / References:
- [Link to mockup]
- [Link to similar feature]
- [Screenshot]

Definition of Done:
- [ ] Feature works E2E
- [ ] Mobile responsive
- [ ] Error handling
- [ ] Documentation updated
- [ ] Deployed to production

Timeline: [If applicable]
Priority: [High / Medium / Low]

Ready when you are.
```

---

### Template 4: Post-Session Checklist

```markdown
## Post-Session Checklist

### Code
- [ ] All changes committed with descriptive messages
- [ ] Pushed to GitHub
- [ ] Deployment successful (if applicable)
- [ ] E2E tested in production/staging

### Documentation
- [ ] PROJECT_STATUS.md updated
- [ ] README.md updated (if major changes)
- [ ] Session notes created (docs/session_notes/)
- [ ] Diagnostic reports saved (if debugging session)

### Verification
- [ ] Site loads (200 HTTP)
- [ ] No console errors
- [ ] Payment flow works (if affected)
- [ ] Mobile responsive (if UI changes)
- [ ] Performance acceptable

### Handoff
- [ ] Next tasks identified
- [ ] Blockers documented
- [ ] Questions answered

### Business
- [ ] Metrics updated (if applicable)
- [ ] Stakeholders informed (if major milestone)
```

---

## 🎓 Lessons Learned (PDFMaster Project)

### Lesson 1: Domain Configuration in Proxy Setup

**Context:**
Railway (backend) + Vercel (frontend proxy) + WordPress

**Problem:**
Infinite redirect loop when WordPress trusted proxy headers

**Solution:**
Force specific domain in wp-config.php instead of trusting X-Forwarded-Host

**Takeaway:**
> In proxy setups, explicitly force domain. Don't blindly trust forwarded headers.

---

### Lesson 2: Filter Priority Matters

**Context:**
Stripe keys loaded via WordPress filters

**Problem:**
Database LIVE keys overridden by Railway env TEST keys

**Root Cause:**
MU plugin filter (priority 20) ran after admin filter (priority 10)

**Solution:**
Update Railway env vars OR adjust filter priority OR remove MU plugin override

**Takeaway:**
> WordPress filter priority: higher number = later execution = final override.
> Document your filter chain.

---

### Lesson 3: Environment Variables Override Database

**Context:**
Stripe mode: database said "live", env vars said "test"

**Problem:**
Must-use plugin read env vars and overrode database

**Solution:**
Synchronize env vars with database mode

**Takeaway:**
> Know your config hierarchy:
> Database → Plugins → MU Plugins → wp-config → ENV (if accessed directly)

---

### Lesson 4: CORS in Multi-Domain Setup

**Context:**
www.pdfspark.app (access) vs pdfspark.app (WordPress URLs)

**Problem:**
Cross-origin request blocked by browser

**Solution:**
Ensure complete domain consistency (database, wp-config, redirects)

**Takeaway:**
> www.example.com ≠ example.com for Same-Origin Policy.
> Pick one and enforce everywhere.

---

### Lesson 5: Must-Use Plugins for Configuration

**Context:**
Need to override WordPress core behavior reliably

**Problem:**
Regular plugins can be disabled, load order uncertain

**Solution:**
Use mu-plugins for critical config (domain forcing, env var handling)

**Takeaway:**
> MU plugins = guaranteed load, can't be disabled.
> Perfect for production config overrides.

---

## 🚀 Advanced Patterns

### Pattern: Diagnostic Report for Complex Issues

**When to Use:**
Multi-layered problem, unclear root cause, need systematic analysis

**Request Template:**
```
Create comprehensive diagnostic report for: [PROBLEM]

Include:
1. Current state analysis (what's configured)
2. Execution flow trace (what happens step-by-step)
3. Root cause identification (why it fails)
4. Solution with verification steps
5. Rollback plan if solution fails

Format: Markdown, save to /tmp/
```

**Real Example:**
```
Session 2025-10-24:
User: "Stripe Live mode enabled but using test keys. Diagnostic report."

Claude created:
- /tmp/stripe-diagnostic-report.md (15 pages)
  - Database state analysis
  - Frontend key loading trace
  - Backend filter priority flow diagram
  - Root cause: Railway env vars override
  - Solution: Update env vars
  - Verification: 10-step checklist
  - Rollback: Revert env vars command
```

---

### Pattern: Session Notes as Project Memory

**When to Use:**
After every significant session (deployment, major feature, debugging)

**Template:**
```
Create session notes for today (YYYY-MM-DD).

Include:
- Executive summary (what shipped)
- Problems fixed (symptom → root cause → solution)
- New features (description → files → testing)
- Technical details (architecture, deployment, config)
- Lessons learned (what we discovered)
- What's next (immediate, short-term, medium-term)

Save to: docs/session_notes/SESSION_NOTES_YYYY-MM-DD.md
```

**Benefits:**
- Complete project history
- Context for future sessions
- Onboarding documentation
- Decision audit trail

---

### Pattern: Git Commit as Documentation

**Philosophy:**
Every commit should tell a story

**Template:**
```
<type>: <summary in imperative mood>

Problem:
<what was wrong, symptom, user impact>

Root Cause:
<why it was happening, technical explanation>

Solution:
<what was changed, how it fixes the root cause>

Files Modified:
- path/to/file1.php (what changed)
- path/to/file2.js (what changed)

Testing:
<how it was verified, E2E steps>

[Optional: Related Issues/PRs]

🤖 Generated with Claude Code
Co-Authored-By: Claude <noreply@anthropic.com>
```

**Benefits:**
- `git log` becomes project documentation
- Easy to find when specific change was made
- Clear reasoning for future debugging

---

## 📊 Success Metrics

### Measuring Effective Claude Code Collaboration

**Velocity Metrics:**
- Lines of code per session
- Features shipped per week
- Time from request to deployment

**Quality Metrics:**
- Emergency fixes per deployment
- Documentation coverage (% of work documented)
- First-time-right ratio (no rework needed)

**PDFMaster Stats (15 sessions, 2025-10-15 → 2025-10-24):**
- **Total commits:** ~30
- **Major features:** 6 (compress UX, all tools UX, homepage P1, Stripe Live, production deployment, domain config)
- **Emergency fixes:** 3 (redirect loop, CORS, Stripe Live)
- **Documentation:** 1,357 lines updated in final session
- **Production deployment:** ✅ (https://www.pdfspark.app)
- **Stripe Live mode:** ✅ (real payments working)

**Key Success Factor:**
> Clear communication + trust + systematic documentation = 🚀

---

## 🎯 Quick Reference

### Do's ✅

1. **Provide full context** - symptom, expected behavior, environment, data
2. **Trust Claude to execute end-to-end** - don't micro-manage
3. **Test after changes** - verify E2E flow manually
4. **Document every session** - update PROJECT_STATUS.md, create session notes
5. **Use descriptive commit messages** - tell the story of what changed and why
6. **Create diagnostic reports for complex issues** - systematic analysis
7. **Maintain configuration hierarchy documentation** - know what overrides what
8. **Ask for templates** - "create template for X based on our workflow"

### Don'ts ❌

1. **Don't give vague requests** - "fix the site" is not actionable
2. **Don't skip testing** - always verify critical paths
3. **Don't ignore documentation** - future you will thank present you
4. **Don't micro-manage** - let Claude execute autonomously
5. **Don't treat symptoms** - find and fix root causes
6. **Don't skip verification after emergency fixes** - test thoroughly
7. **Don't assume config hierarchy** - verify which source has priority
8. **Don't lose session context** - create session notes immediately

---

## 🔮 Advanced Topics

### Working with Production Emergencies

**Mental Model:**
```
Emergency → Stabilize → Diagnose → Fix → Verify → Document → Prevent
```

**1. Stabilize (if site down):**
```
Option A: Rollback last deployment
Option B: Quick band-aid fix
Option C: Maintenance mode

Choose fastest path to restore service.
```

**2. Diagnose:**
```
Create diagnostic report:
- What changed recently (git log)
- Current state (database, config, env vars)
- Error traces (logs, browser console)
- Root cause hypothesis
```

**3. Fix:**
```
Implement solution based on root cause (not symptoms)
Test in staging/local if possible
Deploy to production
```

**4. Verify:**
```
E2E test all critical paths:
- Site loads
- Payment flow works
- No console errors
- Performance acceptable
```

**5. Document:**
```
Session notes:
- What happened (timeline)
- Root cause (technical explanation)
- Solution (what was changed)
- Verification (how tested)
- Prevention (how to avoid in future)
```

**6. Prevent:**
```
Add monitoring:
- Health checks
- Error tracking
- Performance monitoring

Add safeguards:
- MU plugins for critical config
- Database backups
- Deployment checklists
```

---

### Working with Deployment Pipelines

**PDFMaster Deployment Flow:**
```
1. Local development (http://localhost:10003)
2. Git commit + push to main
3. GitHub → Railway webhook
4. Railway auto-build (~3-5 min)
5. Deploy to production
6. Vercel proxy forwards to Railway
7. Live at https://www.pdfspark.app
```

**Monitoring Commands:**
```bash
# 1. Watch deployment status
railway status

# 2. Monitor build logs
railway logs --tail 100 --follow

# 3. Verify deployment
curl -I https://www.pdfspark.app

# 4. Check specific endpoints
curl https://www.pdfspark.app/services
curl https://www.pdfspark.app/wp-json/

# 5. Monitor WordPress debug log (if issues)
railway run tail -f wp-content/debug.log
```

**Rollback Strategy:**
```bash
# Option 1: Git revert
git revert HEAD
git push origin main
→ Railway auto-deploys previous state

# Option 2: Railway redeploy
railway redeploy <deployment-id>

# Option 3: Git reset (destructive)
git reset --hard HEAD~1
git push origin main --force
```

---

### Working with Third-Party Integrations

**Pattern: Stripe Integration Debugging**

**Checklist:**
```markdown
## Stripe Integration Debug

### Keys
- [ ] Test keys start with pk_test_ / sk_test_
- [ ] Live keys start with pk_live_ / sk_live_
- [ ] Webhook secret starts with whsec_
- [ ] Keys match environment (test vs live)

### Mode
- [ ] Database mode correct (wp_options → pdfm_stripe_settings)
- [ ] Frontend receives correct publishable key
- [ ] Backend uses correct secret key
- [ ] No environment variable override

### Payment Flow
- [ ] PaymentIntent created with correct amount
- [ ] Metadata includes file_token
- [ ] Payment succeeds (Stripe Dashboard)
- [ ] Webhook received (if configured)
- [ ] Token marked as paid (database)
- [ ] Download works

### Common Issues
- [ ] "test mode" error → env vars override with test keys
- [ ] Modal not loading → Stripe.js not loaded in HEAD
- [ ] Payment succeeds but download locked → webhook not received
- [ ] CORS error → domain mismatch
```

---

## 📚 Further Reading

**Project Documentation:**
- `README.md` - Quick start, project overview
- `PROJECT_STATUS.md` - Current state, recent work, next tasks
- `docs/PDFMASTER_PROJECT_DOCS.md` - Comprehensive methodology
- `docs/session_notes/` - Historical session logs

**WordPress Best Practices:**
- Filter priority: https://developer.wordpress.org/plugins/hooks/
- Must-use plugins: https://wordpress.org/documentation/article/must-use-plugins/
- wp-config.php: https://wordpress.org/documentation/article/editing-wp-config-php/

**Stripe:**
- Test mode vs Live mode: https://stripe.com/docs/keys
- PaymentIntents: https://stripe.com/docs/payments/payment-intents
- Webhooks: https://stripe.com/docs/webhooks

**Railway:**
- Environment variables: https://docs.railway.app/guides/variables
- Deployments: https://docs.railway.app/guides/deployments

---

## 🎬 Conclusion

**Key Takeaways:**

1. **Communication is everything** - Clear context = fast execution
2. **Trust but verify** - Let Claude work autonomously, then test thoroughly
3. **Document everything** - Session notes + git commits = project memory
4. **Root causes over symptoms** - Fix once, not three times
5. **Configuration hierarchy matters** - Know what overrides what

**The PDFMaster Formula:**
```
Clear Request
  → Full Context
  → Autonomous Execution
  → Thorough Testing
  → Complete Documentation
  → Production Success
```

**Final Advice:**

> Treat Claude Code as your senior development partner, not a junior assistant.
> Give strategic direction, provide context, approve approaches, verify results.
> The better your communication, the better the outcomes.

---

**Document Version:** 1.0
**Last Updated:** 2025-10-24
**Based on:** PDFMaster project (15 sessions)
**Status:** ✅ Production-tested patterns

**Feedback:**
This is a living document. Update it as you discover new patterns or anti-patterns in your work with Claude Code.

**Next Version:**
- Add patterns for multi-tool projects
- Add patterns for team collaboration with Claude Code
- Add advanced debugging workflows
- Add performance optimization patterns

---

**🚀 Happy Building!**
