# GA4 Conversion Tracking - Testing Checklist
**Created:** 2025-10-24
**Feature Branch:** feature/ga4-conversion-tracking
**GA4 Property ID:** G-SG765F3EL7

---

## 📊 Events Implemented

### Conversion Funnel (7 Events Total)

| # | Event Name | Trigger | Parameters | File |
|---|------------|---------|------------|------|
| 1 | `tool_selected` | User clicks tool tab | `tool_name` | processor-scripts.js:172 |
| 2 | `file_uploaded` | File validated & added | `tool_name`, `file_size_kb`, `file_count` | processor-scripts.js:153 |
| 3 | `processing_started` | User clicks "Process" button | `tool_name`, `file_size_kb`, `file_count` | processor-scripts.js:414 |
| 4 | `processing_complete` | Processing successful | `tool_name`, `original_size_kb`, `processed_size_kb`, `compression_ratio` | processor-scripts.js:462 |
| 5 | **`begin_checkout`** | "Pay $0.99" button clicked | `value`, `currency`, `tool_name`, `items[]` | payment-modal.js:30 |
| 6 | **`purchase`** | Payment successful | `transaction_id`, `value`, `currency`, `tool_name`, `items[]` | payment-modal.js:106 |
| 7 | `file_downloaded` | Download button clicked | `tool_name` | processor-scripts.js:688 |

**Note:** Events 5 & 6 are [Google Analytics 4 e-commerce events](https://developers.google.com/analytics/devguides/collection/ga4/ecommerce) (bold).

---

## 🧪 Local Testing Protocol

### Prerequisites
- ✅ GA4 tracking code added to production (`WP_ENVIRONMENT_TYPE === 'production'`)
- ✅ Local environment: `WP_ENVIRONMENT_TYPE === 'local'` (GA4 won't load)
- ✅ Browser DevTools Console open (F12)

### Expected Behavior (Local)
**GA4 scripts will NOT load on local**, but events should log to console:

```javascript
GA4 not loaded - Event skipped: tool_selected {tool_name: "compress"}
GA4 not loaded - Event skipped: file_uploaded {tool_name: "compress", file_size_kb: 2500, file_count: 1}
// ... etc
```

### Test Steps (Local Environment)

#### Test 1: Full Compress Flow

| Step | Action | Expected Console Output | ✓ |
|------|--------|------------------------|---|
| 1 | Navigate to http://localhost:10003/services | Page loads | ☐ |
| 2 | Click "Compress PDF" tab | `GA4 not loaded - Event skipped: tool_selected {tool_name: "compress"}` | ☐ |
| 3 | Upload PDF file (2-3 MB) | `GA4 not loaded - Event skipped: file_uploaded {tool_name: "compress", file_size_kb: ~2500, file_count: 1}` | ☐ |
| 4 | Click "Compress PDF" button | `GA4 not loaded - Event skipped: processing_started {tool_name: "compress", file_size_kb: ~2500, file_count: 1}` | ☐ |
| 5 | Wait for success state | `GA4 not loaded - Event skipped: processing_complete {tool_name: "compress", original_size_kb: ~2500, processed_size_kb: ~500, compression_ratio: 20}` | ☐ |
| 6 | Click "Pay $0.99" button | `GA4 not loaded - E-commerce event skipped: begin_checkout {value: 0.99, currency: "USD", tool_name: "compress", items: [...]}` | ☐ |
| 7 | Enter test card: `4242 4242 4242 4242` | (Stripe Elements loads) | ☐ |
| 8 | Complete payment | `GA4 not loaded - E-commerce event skipped: purchase {transaction_id: "...", value: 0.99, currency: "USD", tool_name: "compress", items: [...]}` | ☐ |
| 9 | Click "Download Your PDF" button | `GA4 not loaded - Event skipped: file_downloaded {tool_name: "compress"}` | ☐ |

**✅ Pass Criteria:** All 7 events logged to console with "GA4 not loaded - Event skipped" prefix.

---

#### Test 2: Merge Flow

| Step | Action | Expected Console Output | ✓ |
|------|--------|------------------------|---|
| 1 | Click "Merge PDFs" tab | `tool_selected {tool_name: "merge"}` | ☐ |
| 2 | Upload 3 PDF files | `file_uploaded {tool_name: "merge", file_size_kb: ~X, file_count: 3}` | ☐ |
| 3 | Click "Merge PDFs" button | `processing_started {tool_name: "merge", ...}` | ☐ |
| 4 | Success state shows | `processing_complete {tool_name: "merge"}` | ☐ |
| 5 | Click "Pay $0.99" | `begin_checkout {..., tool_name: "merge"}` | ☐ |
| 6 | Complete payment | `purchase {..., tool_name: "merge"}` | ☐ |
| 7 | Download file | `file_downloaded {tool_name: "merge"}` | ☐ |

---

#### Test 3: Split Flow

| Step | Action | Expected Console Output | ✓ |
|------|--------|------------------------|---|
| 1 | Click "Split PDF" tab | `tool_selected {tool_name: "split"}` | ☐ |
| 2 | Upload PDF, enter pages "1-3" | `file_uploaded {tool_name: "split", ...}` | ☐ |
| 3 | Process → Pay → Download | All events fire with `tool_name: "split"` | ☐ |

---

#### Test 4: Convert Flow (Images → PDF)

| Step | Action | Expected Console Output | ✓ |
|------|--------|------------------------|---|
| 1 | Click "Convert" tab | `tool_selected {tool_name: "convert"}` | ☐ |
| 2 | Select "Images → PDF" | (no event) | ☐ |
| 3 | Upload 2 JPG files | `file_uploaded {tool_name: "img-to-pdf", file_count: 2}` | ☐ |
| 4 | Process → Pay → Download | All events fire with `tool_name: "img-to-pdf"` | ☐ |

---

## 🚀 Production Testing Protocol

### Prerequisites
- ✅ Feature branch merged to `main`
- ✅ Railway deployment complete (~3-5 min)
- ✅ Site accessible: https://www.pdfspark.app
- ✅ Google Tag Assistant extension installed
- ✅ GA4 Real-time dashboard open

### Verification Step 1: Check GA4 Script Loads

```bash
# Run from terminal
curl -s https://www.pdfspark.app/ | grep "G-SG765F3EL7"

# Expected output:
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SG765F3EL7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-SG765F3EL7');
</script>
```

**✅ Pass:** GA4 code present in HTML `<head>`

---

### Verification Step 2: Google Tag Assistant

1. Install: [Google Tag Assistant (Legacy)](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk)
2. Navigate to: https://www.pdfspark.app/services
3. Click Tag Assistant icon → "Enable"
4. Refresh page (Cmd+R / Ctrl+R)

**Expected Result:**
```
✅ Google Analytics: GA4
   Tag ID: G-SG765F3EL7
   Status: Tag fired successfully
   Request URL: https://www.google-analytics.com/g/collect?...
```

---

### Verification Step 3: GA4 Real-Time Events

**Setup:**
1. Open: https://analytics.google.com/
2. Select property with ID: `G-SG765F3EL7`
3. Navigate: **Reports → Realtime → Event count by Event name**
4. Keep dashboard open in separate tab

**Test Procedure:**

| Step | Action on www.pdfspark.app | GA4 Realtime Dashboard | ✓ |
|------|----------------------------|------------------------|---|
| 1 | Navigate to /services | `page_view` event +1 | ☐ |
| 2 | Click "Compress PDF" tab | `tool_selected` event +1 | ☐ |
| 3 | Upload PDF file | `file_uploaded` event +1 | ☐ |
| 4 | Click "Compress PDF" button | `processing_started` event +1 | ☐ |
| 5 | Wait for success state (5-10s) | `processing_complete` event +1 | ☐ |
| 6 | Click "Pay $0.99" button | `begin_checkout` event +1 ⚠️ | ☐ |
| 7 | Enter REAL credit card (**$0.99 will be charged**) | (Stripe form active) | ☐ |
| 8 | Click "Pay Now" → Success | `purchase` event +1 ⚠️ | ☐ |
| 9 | Click "Download Your PDF" | `file_downloaded` event +1 | ☐ |

**⚠️ Important:** Steps 6-8 will charge $0.99 to your card. Optionally refund in Stripe Dashboard after testing.

**✅ Pass Criteria:**
- All 7 custom events appear in Realtime dashboard
- Events have correct parameters (visible in event details)
- E-commerce events (`begin_checkout`, `purchase`) show revenue data

---

### Verification Step 4: GA4 DebugView (Advanced)

**Setup:**
1. Install: [Google Analytics Debugger extension](https://chrome.google.com/webstore/detail/google-analytics-debugger/jnkmfdileelhofjcijamephohjechhna)
2. Navigate: GA4 → Configure → DebugView
3. Enable extension
4. Open https://www.pdfspark.app/services

**Expected Behavior:**
- See your session in DebugView (green dot = active)
- Each event fires in real-time with full parameter details
- E-commerce events show `items` array structure

---

## 📈 GA4 Configuration (Post-Testing)

### Step 1: Mark Conversions

**Navigate:** GA4 → Configure → Events

**Mark as Conversions:**
- ✅ `purchase` (primary conversion - revenue event)
- ✅ `file_downloaded` (secondary conversion - usage metric)
- ⚪ `begin_checkout` (funnel step, not conversion)

**Why:**
- `purchase` = direct revenue ($0.99 per event)
- `file_downloaded` = actual product delivery (confirms UX works)

---

### Step 2: Create Custom Funnel

**Navigate:** GA4 → Explore → Funnel Exploration

**Funnel Steps:**
1. **Step 1:** `page_view` (baseline traffic)
2. **Step 2:** `tool_selected` (engagement)
3. **Step 3:** `file_uploaded` (intent signal)
4. **Step 4:** `processing_started` (action taken)
5. **Step 5:** `processing_complete` (value delivered - free)
6. **Step 6:** `begin_checkout` (payment intent)
7. **Step 7:** `purchase` (conversion $$)
8. **Step 8:** `file_downloaded` (fulfillment)

**Breakdown Dimensions:**
- `tool_name` (which tool converts best?)
- `source` / `medium` (which traffic source converts?)
- `device_category` (mobile vs desktop conversion)

**Save as:** "PDFMaster Conversion Funnel"

---

### Step 3: Create Custom Report - Revenue by Tool

**Navigate:** GA4 → Explore → Blank exploration

**Setup:**
1. **Dimensions:** `tool_name` (custom parameter)
2. **Metrics:** `Event count`, `Total revenue` (from `purchase` event)
3. **Visualization:** Table
4. **Filters:** Event name = `purchase`

**Expected Output:**
| tool_name | purchase events | total_revenue |
|-----------|----------------|---------------|
| compress | 15 | $14.85 |
| merge | 8 | $7.92 |
| split | 5 | $4.95 |
| convert | 3 | $2.97 |

**Save as:** "Revenue by Tool"

---

## 🐛 Troubleshooting

### Problem: Events Not Firing on Production

**Checklist:**
- [ ] GA4 script loads in page source? (check curl output)
- [ ] Browser console shows "GA4 Event:" messages? (no "not loaded")
- [ ] Tag Assistant shows green checkmark?
- [ ] DebugView shows your session?
- [ ] Correct property ID (G-SG765F3EL7)?

**Common Fixes:**
1. **Hard refresh:** Cmd+Shift+R / Ctrl+Shift+R (clear cache)
2. **Check wp-config.php:** `WP_ENVIRONMENT_TYPE === 'production'`
3. **Verify Railway deployment:** `railway logs --service pdfmaster-wp | grep "started"`
4. **Wait 24-48 hours:** GA4 data processing delay (DebugView is real-time)

---

### Problem: E-commerce Events Missing Revenue

**Symptom:** `begin_checkout` and `purchase` fire but no revenue in GA4

**Solution:**
1. Navigate: GA4 → Configure → Events
2. Find `purchase` event
3. Click "Mark as conversion"
4. Wait 30 minutes for processing

**Verify:** GA4 → Reports → Monetization → Overview (should show revenue)

---

### Problem: Console Shows "GA4 not loaded" on Production

**Symptom:** Console logs "GA4 not loaded - Event skipped" even on www.pdfspark.app

**Root Cause:** `WP_ENVIRONMENT_TYPE` not set to 'production' in wp-config.php

**Fix:**
```bash
# Check Railway wp-config.php
railway run cat wp-config.php | grep "WP_ENVIRONMENT_TYPE"

# Expected: define('WP_ENVIRONMENT_TYPE', 'production');
```

If missing, add to wp-config.php (Railway environment section):
```php
if (getenv('RAILWAY_ENVIRONMENT')) {
    define('WP_ENVIRONMENT_TYPE', 'production');
}
```

---

### Problem: Missing `tool_name` Parameter

**Symptom:** Events fire but `tool_name` shows as "unknown"

**Root Cause:** Radio button not checked when event fires

**Debug:**
```javascript
// Add to browser console on /services page
$('input[name="operation"]:checked').val()
// Should return: compress / merge / split / convert
```

**Fix:** Ensure URL parameter `?tool=compress` is present, or user clicked a tab

---

## 📝 Event Parameter Reference

### Standard Parameters (All Events)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `tool_name` | string | Tool user selected | `compress`, `merge`, `split`, `img-to-pdf`, `pdf-to-img` |

### file_uploaded Parameters

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `file_size_kb` | number | Total size of uploaded files (KB) | `2500` (2.5 MB) |
| `file_count` | number | Number of files uploaded | `3` (for merge) |

### processing_complete Parameters (Compress Only)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `original_size_kb` | number | Original file size (KB) | `2500` |
| `processed_size_kb` | number | Compressed file size (KB) | `500` |
| `compression_ratio` | number | Percentage (processed/original * 100) | `20` (20% of original) |

### E-commerce Parameters (begin_checkout & purchase)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `value` | number | Transaction value | `0.99` |
| `currency` | string | Currency code (ISO 4217) | `USD` |
| `transaction_id` | string | Unique transaction ID (download token) | `token_abc123` (purchase only) |
| `items` | array | Item details (GA4 e-commerce format) | See below |

**Items Array Structure:**
```javascript
items: [{
    item_id: 'pdf_processing',
    item_name: 'PDF Processing (compress)',
    price: 0.99,
    quantity: 1
}]
```

---

## ✅ Final Checklist

### Pre-Deployment
- [ ] All 7 events added to code
- [ ] Helper functions (`trackEvent`) implemented in both files
- [ ] Console logging enabled for debugging
- [ ] Error handling (check `typeof gtag === 'function'`)
- [ ] Parameters match specification
- [ ] E-commerce events use GA4 standard format

### Post-Deployment
- [ ] GA4 script loads on production (curl check)
- [ ] Google Tag Assistant shows green checkmark
- [ ] Real-time dashboard shows events firing
- [ ] DebugView shows full event details
- [ ] E-commerce events show revenue
- [ ] `purchase` marked as conversion in GA4

### GA4 Configuration
- [ ] Custom funnel created (8 steps)
- [ ] Revenue by tool report created
- [ ] Conversions marked (`purchase`, `file_downloaded`)
- [ ] Explore reports saved for monitoring

---

## 📊 Success Metrics (Week 1)

**Track in GA4 → Explore → Custom Funnel:**

| Metric | Target | Why It Matters |
|--------|--------|----------------|
| `tool_selected` / `page_view` | >60% | Engagement: Users interact with tools |
| `file_uploaded` / `tool_selected` | >80% | Intent: Users commit to task |
| `processing_complete` / `processing_started` | >95% | Reliability: Processing works |
| `begin_checkout` / `processing_complete` | >40% | Payment intent: Users willing to pay |
| `purchase` / `begin_checkout` | >50% | Payment success: Checkout UX works |
| `file_downloaded` / `purchase` | >98% | Fulfillment: Download works post-payment |

**Overall Conversion Rate Target:**
- `purchase` / `page_view` = **15-25%** (aggressive for MVP)
- `purchase` / `tool_selected` = **30-40%** (qualified traffic)

---

## 🔗 Resources

**GA4 Documentation:**
- [E-commerce events](https://developers.google.com/analytics/devguides/collection/ga4/ecommerce)
- [Custom events](https://support.google.com/analytics/answer/12229021)
- [DebugView](https://support.google.com/analytics/answer/7201382)

**Stripe Testing:**
- [Test card numbers](https://stripe.com/docs/testing#cards)
- [Webhook testing](https://stripe.com/docs/webhooks/test)

**Tools:**
- [Google Tag Assistant](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk)
- [GA4 Debugger](https://chrome.google.com/webstore/detail/google-analytics-debugger/jnkmfdileelhofjcijamephohjechhna)

---

## 📧 Support

**Questions or Issues:**
- Check console logs first (F12)
- Review troubleshooting section above
- Contact: oleksiakpiotrrafal@gmail.com
- Document issues in: `/docs/session_notes/`

---

**Document Version:** 1.0
**Last Updated:** 2025-10-24
**Status:** ✅ Ready for testing (local + production)
