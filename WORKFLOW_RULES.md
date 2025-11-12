# PDFMaster - Workflow Rules & Development Patterns

**Last Updated:** 2025-11-12
**Status:** Active

This document codifies development patterns, decision frameworks, and coding standards for the PDFMaster project.

---

## Core Principles

### 1. Speed First
- Custom PHP templates over page builders
- Minimize external dependencies
- Hardware-accelerated CSS animations only
- Lazy loading for non-critical assets

### 2. Maintainability
- Clear file structure and naming
- Self-documenting code
- Version all CSS/JS assets for cache busting
- Update documentation every session

### 3. User Experience
- One-click operations (no multi-step forms)
- Clear success/error states
- Mobile-first responsive design
- Real-time feedback during processing

### 4. Security
- Server-side validation always
- No client-side payment bypasses
- Single-use tokens
- Environment variables for secrets

---

## Development Workflow

### Session Start Protocol

**ALWAYS do this at the beginning of every session:**

1. **Read Core Documentation**
   ```bash
   - PROJECT_STATUS.md (current state)
   - CURRENT_ARCHITECTURE.md (system overview)
   - This file (WORKFLOW_RULES.md)
   ```

2. **Check Environment**
   ```bash
   # WordPress
   open http://localhost:10003/wp-admin

   # Stirling PDF health
   curl http://localhost:8080/api/v1/general/health

   # Docker
   docker ps | grep stirling
   ```

3. **Review Recent Changes**
   ```bash
   git log --oneline -10
   git status
   ```

4. **Confirm Task Scope** with user before starting work

### Session End Protocol

**ALWAYS do this at the end of every session:**

1. **Update Documentation**
   - Update PROJECT_STATUS.md with changes made
   - Update CURRENT_ARCHITECTURE.md if architecture changed
   - Create session notes in `docs/archive/` for significant work

2. **Test E2E Flow**
   ```bash
   # Test at least one complete operation
   open http://localhost:10003/services/
   # Upload → Process → Pay → Download
   ```

3. **Commit Changes**
   ```bash
   git add .
   git commit -m "type(scope): description"
   git push origin [branch-name]
   ```

4. **Create PR** if on feature branch

---

## Git Workflow

### Branch Strategy

```bash
main                    # Production branch (auto-deploys to Railway)
├── feature/*          # New features
├── fix/*              # Bug fixes
└── docs/*             # Documentation updates
```

### Commit Message Format

Follow conventional commits:

```
<type>(<scope>): <description>

[optional body]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: CSS/formatting (no logic change)
- `refactor`: Code restructuring (no behavior change)
- `perf`: Performance improvement
- `test`: Adding/updating tests
- `chore`: Maintenance (deps, cleanup)

**Examples:**
```bash
git commit -m "feat(processor): add PDF rotation tool"
git commit -m "fix(payment): handle Stripe webhook timeout"
git commit -m "docs: update architecture diagram"
git commit -m "chore: project cleanup and documentation update"
```

### PR Guidelines

**PR Title:** Same format as commit messages

**PR Description Template:**
```markdown
## Summary
[Brief description of changes]

## Changes
- [Bullet point list of specific changes]

## Testing
- [ ] E2E flow tested (upload → pay → download)
- [ ] Mobile responsive verified
- [ ] Production deployment tested

## Screenshots
[If UI changes]
```

---

## File Organization

### WordPress Plugin Structure

```
wp-content/plugins/[plugin-name]/
├── [plugin-name].php           # Main plugin file
├── includes/                   # PHP classes
│   ├── class-*.php            # One class per file
│   └── admin/                 # Admin-specific classes
├── assets/
│   ├── js/                    # JavaScript files
│   ├── css/                   # Stylesheets
│   └── images/                # Images
└── composer.json              # If external dependencies
```

### Theme Structure

```
wp-content/themes/pdfmaster-theme/
├── style.css                  # Theme metadata
├── functions.php              # Theme functions, hooks
├── page-*.php                 # Custom page templates
├── template-parts/            # Reusable template components
└── assets/
    ├── css/                   # Stylesheets (per-page)
    ├── js/                    # JavaScript (per-page)
    └── images/                # Theme images
```

---

## Naming Conventions

### PHP

**Functions:** Prefix with `pdfm_`
```php
function pdfm_process_pdf( $file ) { ... }
function pdfm_create_payment_intent( $amount ) { ... }
```

**Classes:** PascalCase
```php
class File_Handler { ... }
class Stirling_API { ... }
class Stripe_Handler { ... }
```

**Constants:** UPPERCASE with PDFM_ prefix
```php
define( 'PDFM_PLUGIN_VERSION', '1.0.0' );
define( 'PDFM_MAX_FILE_SIZE', 10485760 );
```

### CSS

**Classes:** Prefix with `.pdfm-`
```css
.pdfm-upload-form { ... }
.pdfm-payment-modal { ... }
.pdfm-success-state { ... }
```

**BEM Naming** for component variants:
```css
.pdfm-button { ... }
.pdfm-button--primary { ... }
.pdfm-button--disabled { ... }
```

### JavaScript

**Variables:** camelCase
```javascript
const downloadToken = '...';
const paymentIntent = '...';
```

**Functions:** camelCase
```javascript
function handleUpload() { ... }
function createPaymentIntent() { ... }
```

**Event Handlers:** `handle` prefix
```javascript
function handlePaymentSuccess() { ... }
function handleProcessError() { ... }
```

---

## Code Standards

### PHP Standards

**Version:** PHP 8.4+

**Type Hints:** Always use
```php
// Good
function process_file( string $file_path ): array {
    return [ 'success' => true ];
}

// Bad
function process_file( $file_path ) {
    return [ 'success' => true ];
}
```

**Error Handling:**
```php
try {
    $result = $stirling_api->compress( $file );
} catch ( Exception $e ) {
    error_log( 'Stirling API Error: ' . $e->getMessage() );
    wp_send_json_error( [ 'message' => 'Processing failed' ] );
}
```

**WordPress Hooks:**
```php
// Always check if function exists
if ( ! function_exists( 'pdfm_custom_function' ) ) {
    function pdfm_custom_function() { ... }
}

// Use proper hook priorities
add_action( 'wp_enqueue_scripts', 'pdfm_enqueue_assets', 10 );
```

### JavaScript Standards

**Modern ES6+:**
```javascript
// Use const/let (never var)
const apiUrl = '/wp-admin/admin-ajax.php';
let downloadToken = null;

// Use arrow functions
const handleClick = () => { ... };

// Use async/await
async function processFile() {
    try {
        const response = await fetch(apiUrl, { ... });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Process error:', error);
    }
}
```

**jQuery:** Only use if WordPress dependencies require it
```javascript
// Prefer vanilla JS
document.querySelector('.pdfm-button').addEventListener('click', handleClick);

// Only use jQuery when necessary (WordPress admin)
jQuery(document).ready(function($) {
    $('.pdfm-admin-toggle').on('change', handleToggle);
});
```

### CSS Standards

**Mobile-First:**
```css
/* Base styles (mobile) */
.pdfm-container {
    padding: 1rem;
}

/* Tablet */
@media (min-width: 768px) {
    .pdfm-container {
        padding: 2rem;
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .pdfm-container {
        padding: 3rem;
    }
}
```

**Hardware Acceleration:**
```css
/* Use transform instead of top/left */
.pdfm-modal {
    transform: translateY(-100%);
    transition: transform 0.3s ease;
}

.pdfm-modal.active {
    transform: translateY(0);
}
```

---

## Decision Framework

### When to Add a New Feature

**Ask these questions:**

1. **Does it align with core value proposition?**
   - Simple, fast, pay-per-action PDF processing
   - If no → reject

2. **Does it require user accounts?**
   - Core principle: No accounts
   - If yes → find alternative approach

3. **Will it slow down the UX?**
   - Target: <2s page load, <5s processing
   - If yes → optimize or reject

4. **Can it be monetized at $0.99?**
   - All features must justify the flat price
   - If no → reject or bundle

5. **Is it technically feasible with Stirling PDF?**
   - Check Stirling API documentation
   - If no → research alternatives

### When to Refactor

**Refactor if:**
- Code is duplicated in 3+ places
- Function exceeds 50 lines
- Class exceeds 300 lines
- Performance issue identified
- Security vulnerability found

**Don't refactor if:**
- Feature works and is maintainable
- Refactor would break existing functionality
- No clear benefit to UX or maintainability

### When to Create a New Plugin

**Create new plugin if:**
- Feature is self-contained (payments, analytics, etc.)
- Can be disabled independently
- Has its own set of dependencies

**Don't create new plugin if:**
- Tightly coupled to core processor logic
- Shares majority of code with existing plugin
- Adds minimal functionality (<200 lines)

---

## Testing Protocol

### Before Every Commit

1. **Visual Test**
   - Check all affected pages in browser
   - Test mobile responsive (DevTools)
   - Verify no console errors

2. **Functional Test**
   - Test modified functionality end-to-end
   - Test error states
   - Test edge cases (large files, network errors)

3. **Cross-Browser** (for UI changes)
   - Chrome (primary)
   - Safari
   - Firefox

### Before Every Deployment

1. **E2E Flow** (all 4 tools)
   - Upload file → Process → Pay → Download
   - Test with real files (not tiny test files)
   - Verify Stripe payment succeeds

2. **Payment Test**
   - Test card: 4242 4242 4242 4242
   - Verify token marking
   - Verify download gating

3. **Stirling PDF Health**
   ```bash
   curl http://localhost:8080/api/v1/general/health
   ```

### After Production Deployment

1. **Smoke Test**
   - Visit https://www.pdfspark.app
   - Test one complete operation
   - Check Stripe dashboard for test payment

2. **Monitor Logs**
   ```bash
   railway logs --tail 100 --follow
   ```

3. **Check Error Tracking** (Sentry, once configured)

---

## Asset Management

### CSS Versioning

**Always version CSS/JS assets:**
```php
wp_enqueue_style(
    'pdfm-homepage',
    get_template_directory_uri() . '/assets/css/homepage-p1.css',
    array(),
    '1.0.2',  // Bump this on every change
    'all'
);
```

### Image Optimization

- **Format:** WebP with PNG fallback
- **Compression:** 80% quality
- **Dimensions:** Exact size needed (no oversized images)
- **Lazy Loading:** For below-the-fold images

### Script Loading

**Load in footer by default:**
```php
wp_enqueue_script(
    'pdfm-processor',
    plugin_dir_url( __FILE__ ) . 'assets/js/processor-scripts.js',
    array( 'jquery' ),
    '1.0.1',
    true  // Load in footer
);
```

**Localize data for AJAX:**
```php
wp_localize_script( 'pdfm-processor', 'pdfmData', array(
    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    'nonce'   => wp_create_nonce( 'pdfm_process_nonce' ),
) );
```

---

## Security Checklist

### For Every AJAX Endpoint

```php
// 1. Verify nonce
if ( ! check_ajax_referer( 'pdfm_process_nonce', 'nonce', false ) ) {
    wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
}

// 2. Validate input
$file = isset( $_FILES['file'] ) ? $_FILES['file'] : null;
if ( ! $file || $file['error'] !== UPLOAD_ERR_OK ) {
    wp_send_json_error( [ 'message' => 'Invalid file' ] );
}

// 3. Sanitize data
$file_name = sanitize_file_name( $file['name'] );

// 4. Check file type
$allowed_types = [ 'application/pdf' ];
if ( ! in_array( $file['type'], $allowed_types ) ) {
    wp_send_json_error( [ 'message' => 'Invalid file type' ] );
}

// 5. Process safely
// ...
```

### For Payment Handling

```php
// Always verify payment server-side
$payment_intent = \Stripe\PaymentIntent::retrieve( $intent_id );

if ( $payment_intent->status === 'succeeded' ) {
    // Mark token as paid
    $this->mark_token_paid( $payment_intent->metadata->file_token );
}
```

### For File Downloads

```php
// Verify token before streaming file
$token_data = $this->get_token_data( $token );

if ( ! $token_data || ! $token_data['paid'] ) {
    wp_die( 'Invalid or unpaid token', 'Unauthorized', [ 'response' => 403 ] );
}

// Stream file securely
readfile( $token_data['file_path'] );
```

---

## Performance Guidelines

### Target Metrics

- **Page Load:** <2 seconds (LCP)
- **Processing Time:** <5 seconds (compress)
- **Time to Interactive:** <3 seconds (TTI)
- **First Contentful Paint:** <1 second (FCP)

### Optimization Techniques

1. **Minimize Dependencies**
   - Only load Stripe.js on pages that need it
   - Conditional script loading

2. **Optimize Images**
   - Use WebP format
   - Lazy load below-the-fold
   - Serve via CDN (Vercel)

3. **Cache Static Assets**
   - Version CSS/JS files
   - Set far-future expires headers
   - Use CDN for static files

4. **Database Optimization**
   - Index frequently queried columns
   - Clean up old download tokens (cron job)
   - Use transients for repeated queries

---

## Documentation Standards

### Code Comments

**When to comment:**
- Complex logic or algorithms
- Workarounds or hacks
- Security-critical sections
- Integration points with external APIs

**When NOT to comment:**
- Self-explanatory code
- Obvious functionality
- Repeating what the code says

**Good Example:**
```php
// Stirling PDF requires expectedOutputSize even though it's ignored
// This is a known API quirk - see GitHub issue #1234
$params['expectedOutputSize'] = '25KB';
```

**Bad Example:**
```php
// Set file name to uploaded file name
$file_name = $uploaded_file['name'];
```

### Inline Documentation

**Use PHPDoc blocks:**
```php
/**
 * Process PDF file via Stirling API
 *
 * @param string $file_path Absolute path to PDF file
 * @param string $operation Operation type (compress|merge|split|convert)
 * @return array Result with download_token and file_url
 * @throws Exception If Stirling API fails
 */
function pdfm_process_pdf( string $file_path, string $operation ): array {
    // ...
}
```

---

## Deployment Checklist

### Pre-Deployment

- [ ] All tests passing
- [ ] E2E flow verified locally
- [ ] Documentation updated
- [ ] No console errors
- [ ] Mobile responsive verified
- [ ] PR reviewed and approved

### Deployment

- [ ] Merge to `main` branch
- [ ] Railway auto-deploy triggered
- [ ] Vercel auto-deploy triggered
- [ ] Monitor Railway logs during deployment

### Post-Deployment

- [ ] Smoke test on production URL
- [ ] Verify Stripe Live mode working
- [ ] Check error logs (Railway)
- [ ] Monitor for 15 minutes

### Rollback Procedure

If production issues occur:

```bash
# 1. Identify last working commit
git log --oneline -10

# 2. Revert to last working commit
git revert [bad-commit-sha]
git push origin main

# 3. Railway auto-deploys the revert

# 4. Verify production is working

# 5. Fix issue on feature branch and re-deploy
```

---

## Common Patterns

### AJAX Handler Pattern

```php
function pdfm_handle_ajax_action() {
    // 1. Verify nonce
    check_ajax_referer( 'pdfm_nonce', 'nonce' );

    // 2. Validate input
    $input = sanitize_text_field( $_POST['input'] );

    // 3. Process
    try {
        $result = do_something( $input );
        wp_send_json_success( [ 'data' => $result ] );
    } catch ( Exception $e ) {
        error_log( $e->getMessage() );
        wp_send_json_error( [ 'message' => 'Error occurred' ] );
    }
}
add_action( 'wp_ajax_pdfm_action', 'pdfm_handle_ajax_action' );
add_action( 'wp_ajax_nopriv_pdfm_action', 'pdfm_handle_ajax_action' );
```

### Frontend AJAX Pattern

```javascript
async function performAction(inputData) {
    try {
        const response = await fetch(pdfmData.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'pdfm_action',
                nonce: pdfmData.nonce,
                input: inputData
            })
        });

        const result = await response.json();

        if (result.success) {
            return result.data;
        } else {
            throw new Error(result.data.message);
        }
    } catch (error) {
        console.error('Action failed:', error);
        throw error;
    }
}
```

---

## Troubleshooting

### Common Issues

**Issue:** CSS/JS not loading
- **Cause:** Version not bumped after change
- **Fix:** Increment version in `wp_enqueue_*` call

**Issue:** AJAX returning 0
- **Cause:** Hook not registered properly
- **Fix:** Check `add_action` calls, verify nonce

**Issue:** Payment not marking token
- **Cause:** Webhook not configured or failing
- **Fix:** Check Stripe webhook logs, verify signature

**Issue:** Stirling PDF timeout
- **Cause:** Large file or slow processing
- **Fix:** Increase timeout, show progress indicator

---

## References

- **WordPress Coding Standards:** https://developer.wordpress.org/coding-standards/
- **PHP Documentation:** https://www.php.net/docs.php
- **Stripe API Docs:** https://stripe.com/docs/api
- **Stirling PDF API:** http://localhost:8080/swagger-ui/index.html

---

**Status:** ✅ Active workflow rules
**Maintained By:** Development team
**Review Frequency:** Quarterly or when major changes occur
