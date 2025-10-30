# Uptime Monitoring Setup Guide for PDFSpark

## Overview

PDFSpark uses **UptimeRobot** (free tier) to monitor site health and availability. The health check endpoint monitors 3 critical services: WordPress, MySQL database, and Stirling PDF API.

---

## 1. Health Check Endpoint

### Endpoint Details
- **URL:** `https://www.pdfspark.app/wp-json/pdfmaster/v1/health`
- **Method:** GET
- **Authentication:** None (public endpoint)
- **Response Time:** < 2 seconds

### Response Format

**Healthy (200 OK):**
```json
{
  "status": "healthy",
  "timestamp": "2025-10-30 14:30:22",
  "checks": {
    "wordpress": true,
    "database": true,
    "stirling_pdf": true
  },
  "version": "0.1.0"
}
```

**Unhealthy (503 Service Unavailable):**
```json
{
  "status": "unhealthy",
  "timestamp": "2025-10-30 14:30:22",
  "checks": {
    "wordpress": true,
    "database": true,
    "stirling_pdf": false
  },
  "version": "0.1.0"
}
```

### Test Endpoint Locally

```bash
# Test endpoint is accessible
curl -I http://localhost:10003/wp-json/pdfmaster/v1/health

# View full response
curl http://localhost:10003/wp-json/pdfmaster/v1/health | jq

# Test on production
curl https://www.pdfspark.app/wp-json/pdfmaster/v1/health | jq
```

Expected output:
```
HTTP/2 200
content-type: application/json
...

{
  "status": "healthy",
  ...
}
```

---

## 2. UptimeRobot Account Setup

### Create Account
1. Go to https://uptimerobot.com/signUp
2. Sign up with email (free tier)
3. Verify email address
4. Login to dashboard

### Free Tier Limits
- ✅ **50 monitors** (we only need 1)
- ✅ **5-minute checks** (sufficient)
- ✅ **Email + SMS alerts** (2 alert contacts)
- ✅ **Unlimited checks** (no monthly limit)
- ✅ **Public status page** (optional)

---

## 3. Create Monitor

### Add New Monitor

1. Login to UptimeRobot dashboard
2. Click **"+ Add New Monitor"**
3. Configure monitor:

**Monitor Type:** HTTP(s)

**Friendly Name:** PDFSpark Production

**URL:** `https://www.pdfspark.app/wp-json/pdfmaster/v1/health`

**Monitoring Interval:** 5 minutes

**Monitor Timeout:** 30 seconds

**HTTP Method:** GET (auto-detected)

**Expected Status Code:** 200

**Keyword Monitoring:** (Optional)
- Enable: Yes
- Keyword: `"status":"healthy"`
- Type: Contains

4. Click **"Create Monitor"**

### Configure Alerts

**Alert Contacts:**
1. Click **"My Settings"** → **"Alert Contacts"**
2. Add email: **[your-email@example.com]**
3. Verify email address
4. Optional: Add SMS alert (limited to 2)

**Alert Triggers:**
- **Down:** Monitor becomes unresponsive (HTTP ≠ 200)
- **Up:** Monitor recovers
- **Keyword Not Found:** (if keyword monitoring enabled)

---

## 4. Test Alert System

### Method 1: Pause Service (Recommended)

```bash
# Temporarily pause Railway service
# Railway Dashboard → pdfmaster-wp → Settings → Pause Service

# Wait 5-10 minutes for UptimeRobot to detect downtime

# Check UptimeRobot dashboard - should show "Down" status
# Check email - should receive alert

# Resume service
# Railway Dashboard → Resume Service

# Wait 5 minutes - should show "Up" status
# Check email - should receive recovery notification
```

### Method 2: Simulate Stirling PDF Failure

```bash
# Pause Stirling PDF service only
# Railway Dashboard → stirling-pdf → Pause Service

# Health check will return 503 (Stirling unhealthy)
# UptimeRobot will detect failure

# Resume Stirling service
```

### Method 3: Temporary Break Endpoint (Not Recommended)

```bash
# Temporarily add to wp-config.php:
# define('PDFM_DISABLE_HEALTH_CHECK', true);

# Deploy to Railway
# Health check will return 404

# Remove line and redeploy
```

---

## 5. UptimeRobot Dashboard

### Key Metrics to Monitor

**Uptime Ratio:**
- Target: **99.9%** (minimal downtime)
- Calculate: (Total uptime / Total monitoring time) × 100

**Response Time:**
- Target: **< 2 seconds** average
- Spike = potential issue (investigate)

**Downtime Events:**
- Review downtime logs monthly
- Identify patterns (time of day, frequency)

### Dashboard Features

**Logs Tab:**
- View all uptime/downtime events
- Filter by date range
- Export to CSV

**Response Times Graph:**
- Visual trend of response times
- Identify performance degradation

**Public Status Page:**
- Optional: Share with users
- Shows real-time status
- Custom domain support (paid tier)

---

## 6. Alert Configuration Best Practices

### Alert Frequency

**Down Alert:**
- **Notify when:** 2 consecutive failures (10 minutes)
- **Why:** Avoids false positives from transient network issues

**Recovery Alert:**
- **Notify when:** Immediately on recovery
- **Why:** Confirms issue resolved

**Threshold:**
- Set minimum downtime: 5 minutes
- Prevents alerts for brief (< 5 min) outages

### Alert Channels

**Email (Primary):**
- ✅ Reliable, permanent record
- ✅ Free, unlimited

**SMS (Secondary):**
- ⚠️ Limited to 2 contacts on free tier
- ⚠️ Reserve for critical P0 alerts only

**Webhook (Optional):**
- Send to Slack/Discord
- Trigger automated responses

---

## 7. Monitoring Integrations

### Slack Integration

1. UptimeRobot → My Settings → Alert Contacts
2. Add Slack webhook:
   - Go to Slack → Apps → Incoming Webhooks
   - Create webhook for #alerts channel
   - Copy webhook URL
   - Add to UptimeRobot: Alert Type → Webhook
   - URL: `https://hooks.slack.com/services/...`

**Slack Message Preview:**
```
🔴 PDFSpark Production is DOWN
URL: https://www.pdfspark.app/wp-json/pdfmaster/v1/health
Time: 2025-10-30 14:30:22
Duration: 5 minutes
```

### Discord Integration

Similar to Slack - use Discord webhook URL

### PagerDuty Integration (Paid)

For 24/7 on-call rotation (when team grows)

---

## 8. Incident Response Workflow

### When Alert Received

**Step 1: Verify Issue (2 min)**
```bash
# Check production site
curl -I https://www.pdfspark.app/

# Check health endpoint
curl https://www.pdfspark.app/wp-json/pdfmaster/v1/health | jq

# Check Railway status
railway status --service pdfmaster-wp
```

**Step 2: Identify Root Cause (5 min)**
```bash
# Check Railway logs
railway logs --tail 100 --service pdfmaster-wp

# Check which service failed
curl https://www.pdfspark.app/wp-json/pdfmaster/v1/health | jq '.checks'

# If Stirling PDF down:
railway logs --tail 50 --service stirling-pdf
```

**Step 3: Apply Fix (10 min)**

**If WordPress down:**
```bash
# Restart service
railway service restart pdfmaster-wp
```

**If Database down:**
```bash
# Check Railway MySQL status
railway service --name mysql

# Restart if needed (rare)
```

**If Stirling PDF down:**
```bash
# Restart Stirling service
railway service restart stirling-pdf
```

**Step 4: Verify Recovery (2 min)**
```bash
# Check health endpoint
curl https://www.pdfspark.app/wp-json/pdfmaster/v1/health

# Test full flow
# Upload PDF → Process → Pay → Download
```

**Step 5: Document Incident**
Add entry to `docs/INCIDENT_LOG.md`:
```markdown
## 2025-10-30 14:30 - Stirling PDF Service Down

**Duration:** 15 minutes
**Impact:** Users unable to process PDFs
**Root Cause:** Stirling PDF container OOM (out of memory)
**Resolution:** Restarted service, increased memory allocation
**Prevention:** Monitor Stirling memory usage, add alerts
```

---

## 9. Advanced Monitoring (Optional)

### Multi-Location Checks

**Upgrade to Paid Tier:**
- Check from multiple regions (US, EU, Asia)
- Detect regional outages
- Cost: ~$10/month

### Custom Monitoring Endpoints

**Additional endpoints to monitor:**
1. `/wp-json/pdfmaster/v1/health` (main)
2. `/` (homepage loads)
3. `/services/` (tool page loads)

**Setup:**
- Create 3 separate monitors in UptimeRobot
- Different alert thresholds for each

### Heartbeat Monitoring

**For Background Jobs:**
- Monitor cron jobs (file cleanup)
- Use Healthchecks.io (free tier)
- Ping after successful job completion

---

## 10. Troubleshooting

### "Monitor keeps showing down but site works"

**Cause:** UptimeRobot IP blocked by firewall or rate limiting

**Fix:**
```bash
# Whitelist UptimeRobot IPs (if using firewall)
# IPs: https://uptimerobot.com/kb/locations-and-ips/

# Check Railway logs for 429/403 errors
railway logs --tail 100 | grep "uptimerobot"
```

### "False positive alerts"

**Cause:** Transient network issues or API timeouts

**Fix:**
- Increase "Notify when down" threshold to 2-3 failures
- Increase timeout to 60 seconds
- Enable keyword monitoring for stricter checks

### "No alerts received"

**Cause:** Alert contact not verified or notifications disabled

**Fix:**
1. UptimeRobot → My Settings → Alert Contacts
2. Verify email/phone status: "Verified" ✅
3. Check spam folder
4. Test notification: "Send Test Alert"

---

## 11. Monitoring Checklist

### Initial Setup
- [ ] Health endpoint accessible (200 OK)
- [ ] UptimeRobot account created
- [ ] Monitor configured (5-min checks)
- [ ] Email alert added and verified
- [ ] Test alert received (pause service test)
- [ ] Recovery alert received

### Weekly Checks
- [ ] Review uptime ratio (target: 99.9%)
- [ ] Check response time trends
- [ ] Review downtime logs
- [ ] Verify no recurring issues

### Monthly Review
- [ ] Analyze downtime patterns
- [ ] Update incident response docs
- [ ] Review alert thresholds
- [ ] Test alert system (scheduled maintenance)

---

## Quick Reference

**Health Endpoint:** `https://www.pdfspark.app/wp-json/pdfmaster/v1/health`
**UptimeRobot Dashboard:** https://uptimerobot.com/dashboard
**Test Alert:** Railway → Pause Service → Wait 10 min
**Support:** https://uptimerobot.com/contact

---

## Status

✅ **Health Endpoint:** Implemented in code
⏳ **Pending:** UptimeRobot account setup (user action)
⏳ **Pending:** Monitor configured
⏳ **Pending:** Alert test completed

**Estimated Setup Time:** 20 minutes
**Maintenance:** 5 min/week (review dashboard)
