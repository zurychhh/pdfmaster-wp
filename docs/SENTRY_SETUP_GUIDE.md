# Sentry Setup Guide for PDFSpark

## 1. Create Sentry Account

1. Go to https://sentry.io/signup/
2. Sign up with GitHub or email
3. Choose plan: **Developer (Free)**
   - 5,000 errors/month
   - 10,000 performance units/month
   - 1 GB attachments

## 2. Create Project

1. Click **"Create Project"**
2. Select platform: **PHP**
3. Set alert frequency: **Alert me on every new issue**
4. Project name: **pdfmaster-production**
5. Click **"Create Project"**

## 3. Get DSN Key

After project creation, you'll see:
```
https://xxx@xxx.ingest.sentry.io/xxx
```

Copy this DSN - you'll need it for Railway.

## 4. Configure Railway Environment Variable

```bash
# Set Sentry DSN in Railway
railway variables set SENTRY_DSN="https://xxx@xxx.ingest.sentry.io/xxx"

# Verify variable is set
railway variables
```

Alternatively, via Railway Dashboard:
1. Go to Railway project → **pdfmaster-wp** service
2. Click **Variables** tab
3. Add new variable:
   - Key: `SENTRY_DSN`
   - Value: `https://xxx@xxx.ingest.sentry.io/xxx`
4. Click **Add**
5. Service will auto-redeploy

## 5. Test Error Capture

### Method 1: Trigger Test Error via WP Admin

Add this temporary code to `wp-content/mu-plugins/sentry-test.php`:

```php
<?php
// Temporary - delete after testing
add_action('admin_init', function() {
    if (isset($_GET['sentry_test'])) {
        if (function_exists('\\Sentry\\captureMessage')) {
            \Sentry\captureMessage('Test error from PDFSpark', \Sentry\Severity::error());
            wp_die('Test error sent to Sentry. Check dashboard.');
        } else {
            wp_die('Sentry not initialized');
        }
    }
});
```

Visit: `https://www.pdfspark.app/wp-admin/?sentry_test=1`

### Method 2: Trigger Real Error

Try uploading an invalid PDF or causing a Stirling API timeout. Error should appear in Sentry dashboard within 1 minute.

## 6. Configure Alerts

In Sentry project settings:

1. **Alerts** → **Create Alert Rule**
2. Choose: **Issues**
3. Conditions:
   - When: **A new issue is created**
   - If: **The issue's level is equal to error**
4. Actions:
   - **Send a notification via email**
   - To: **[your-email]**
5. Name: **Critical Errors - Immediate Notification**
6. Save

## 7. Verify Setup

Checklist:
- [ ] Sentry account created
- [ ] Project "pdfmaster-production" created
- [ ] DSN copied
- [ ] Railway env var `SENTRY_DSN` set
- [ ] Service redeployed
- [ ] Test error captured in dashboard
- [ ] Alert email received

## 8. Monitoring Sentry

### Daily Checks
- Login to Sentry dashboard
- Check **Issues** tab
- Resolve/archive non-critical issues

### Key Metrics to Watch
- **Error Rate**: Should be <5 errors/hour under normal traffic
- **Most Common Errors**: Prioritize fixing top 3
- **Payment Errors**: Investigate immediately (affects revenue)

## 9. Sentry Best Practices

### What Gets Captured
✅ **Captured:**
- Stripe payment failures
- Stirling PDF API errors
- File upload errors
- Database errors
- PHP exceptions/errors

❌ **Not Captured:**
- User input validation errors (handled gracefully)
- 404s (normal)
- Debug notices (filtered out)

### Performance Impact
- Minimal: ~5-10ms per request
- Only captures errors (not every request)
- 20% of requests sampled for performance monitoring

## 10. Troubleshooting

### "Sentry not initialized" Error

**Cause:** SENTRY_DSN env var not set or Composer vendor not installed

**Fix:**
```bash
# Check Railway variables
railway variables

# Verify vendor directory exists
railway run ls -la /app/wp-content/plugins/pdfmaster-processor/vendor

# Reinstall if missing
railway run "cd /app/wp-content/plugins/pdfmaster-processor && composer install --no-dev"
```

### No Errors Appearing in Sentry

**Cause:** Errors are being handled gracefully (WP_Error) but not thrown

**Fix:** Check that error capture is added to:
- `class-stirling-api.php:199-223` (Stirling errors)
- `class-stripe-handler.php:84-98` (Stripe errors)
- `class-stripe-handler.php:131-142` (AJAX errors)

### Too Many Errors (Quota Exceeded)

**Cause:** Bug causing error loop

**Fix:**
1. Identify issue in Sentry → **Issues** → Sort by **Events**
2. Fix bug immediately
3. Deploy fix
4. Archive issue in Sentry

## 11. Upgrading Sentry

Free tier limits (5K errors/month) should be sufficient for MVP.

If you need more:
- **Team Plan**: $26/month - 50K errors
- **Business Plan**: $80/month - 150K errors

---

## Quick Reference

**Sentry Dashboard:** https://sentry.io/organizations/[your-org]/issues/
**Railway Variables:** `railway variables`
**Test Error:** `https://www.pdfspark.app/wp-admin/?sentry_test=1`
**Support:** https://docs.sentry.io/platforms/php/

---

**Status:** Ready for production ✅
**Estimated Setup Time:** 15 minutes
