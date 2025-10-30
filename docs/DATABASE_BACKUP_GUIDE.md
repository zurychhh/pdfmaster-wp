# Database Backup Guide for PDFSpark

## Overview

PDFSpark uses a two-tier backup strategy:
1. **Railway Automated Backups** (Primary) - Daily snapshots with 7-day retention
2. **Manual Script Backups** (Secondary) - On-demand exports for critical changes

---

## 1. Railway Automated Backups (Recommended)

### Enable Automated Backups

**Via Railway Dashboard:**
1. Login to Railway: https://railway.app/
2. Navigate to your project → **MySQL** service
3. Click **Settings** tab
4. Scroll to **Backups** section
5. Enable **Automated Backups**
6. Configure:
   - **Frequency:** Daily
   - **Time:** 3:00 AM UTC (low-traffic period)
   - **Retention:** 7 days (free tier)
7. Click **Save**

**Via Railway CLI:**
```bash
# View current backup settings
railway service --name mysql
railway variables

# Enable via Dashboard (CLI doesn't support backup config yet)
```

### Restore from Railway Backup

**Via Dashboard:**
1. Railway project → **MySQL** service
2. **Data** tab → **Backups** section
3. Select backup by date
4. Click **Restore Backup**
5. Confirm restoration (⚠️ overwrites current data)
6. Wait 2-5 minutes for completion

**Verify Restoration:**
```bash
# Check database tables
railway run wp db query "SHOW TABLES" --allow-root

# Check latest post
railway run wp post list --post_type=page --posts_per_page=5 --allow-root
```

---

## 2. Manual Script Backups

### Create Manual Backup

```bash
# Run backup script
railway run bash /app/scripts/backup-db.sh

# Output will show:
# ✓ Backup directory created: /tmp/backups
# ✓ Database exported successfully
# ✓ Backup compressed: /tmp/backups/pdfmaster_20251030_143022.sql.gz
# ✓ Backup completed successfully!
```

### Download Backup to Local Machine

```bash
# List available backups
railway run ls -lh /tmp/backups/

# Download specific backup
railway run cat /tmp/backups/pdfmaster_20251030_143022.sql.gz > backup-20251030.sql.gz

# Decompress locally
gunzip backup-20251030.sql.gz
```

### Restore from Manual Backup

```bash
# Upload backup to Railway
railway run bash /app/scripts/restore-db.sh /tmp/backups/pdfmaster_20251030_143022.sql.gz

# Follow prompts (type 'yes' to confirm)
```

**⚠️ WARNING:** Restoration overwrites current database. Always create a backup before restoring.

---

## 3. Pre-Deployment Backup Protocol

**Before any major deployment:**

```bash
# 1. Create backup
railway run bash /app/scripts/backup-db.sh

# 2. Download backup locally (safety)
railway run cat /tmp/backups/pdfmaster_$(date +%Y%m%d)*.sql.gz > backup-pre-deployment.sql.gz

# 3. Verify backup file size
ls -lh backup-pre-deployment.sql.gz

# 4. Proceed with deployment
git push origin main

# 5. If deployment fails, restore backup:
railway run bash /app/scripts/restore-db.sh /tmp/backups/pdfmaster_YYYYMMDD_HHMMSS.sql.gz
```

---

## 4. Backup Schedule

### Automated (Railway)
- **Frequency:** Daily at 3:00 AM UTC
- **Retention:** 7 days (168 hours)
- **Size Limit:** Varies by Railway plan (free tier: sufficient for MVP)

### Manual (Script)
- **Before major deployments** (pricing changes, database migrations)
- **Before WordPress/plugin updates**
- **After importing large datasets**
- **Weekly manual backup** (download to local/cloud storage)

---

## 5. Backup Verification

### Test Restore Procedure (Monthly)

```bash
# 1. Create test backup
railway run bash /app/scripts/backup-db.sh

# 2. Note current state
railway run wp post list --post_type=page --allow-root > before-restore.txt

# 3. Restore backup
railway run bash /app/scripts/restore-db.sh /tmp/backups/pdfmaster_*.sql.gz

# 4. Verify state matches
railway run wp post list --post_type=page --allow-root > after-restore.txt

# 5. Compare
diff before-restore.txt after-restore.txt  # Should be identical
```

### Check Backup Integrity

```bash
# Verify backup file is not corrupted
railway run gzip -t /tmp/backups/pdfmaster_20251030_143022.sql.gz

# Check backup contains data
railway run zcat /tmp/backups/pdfmaster_20251030_143022.sql.gz | head -20
```

---

## 6. Disaster Recovery Procedure

### Scenario: Database Corrupted/Lost

**Step 1: Assess Damage**
```bash
# Try to connect to database
railway run wp db check --allow-root

# Check table status
railway run wp db query "SHOW TABLE STATUS" --allow-root
```

**Step 2: Restore from Most Recent Backup**

**Option A: Railway Automated Backup (Fastest)**
1. Railway Dashboard → MySQL → Backups
2. Select yesterday's backup
3. Click Restore
4. Wait 3-5 minutes

**Option B: Manual Script Backup**
```bash
# List available backups
railway run ls -lh /tmp/backups/

# Restore most recent
railway run bash /app/scripts/restore-db.sh /tmp/backups/pdfmaster_20251030_143022.sql.gz
```

**Step 3: Verify Restoration**
```bash
# Check site loads
curl -I https://www.pdfspark.app/

# Check admin login works
# Visit: https://www.pdfspark.app/wp-admin

# Check database tables exist
railway run wp db tables --allow-root

# Test payment flow
# Upload PDF → Process → Pay → Download
```

**Step 4: Document Incident**
- Add entry to `docs/INCIDENT_LOG.md`
- Note: Date, issue, restore time, data loss (if any)

---

## 7. Backup to External Storage (Optional)

### Using AWS S3

**Setup:**
```bash
# Install AWS CLI in Dockerfile
# Add to Dockerfile:
RUN apt-get update && apt-get install -y awscli

# Set Railway env vars
railway variables set AWS_ACCESS_KEY_ID="your-key"
railway variables set AWS_SECRET_ACCESS_KEY="your-secret"
railway variables set BACKUP_S3_BUCKET="pdfmaster-backups"
```

**Modify backup-db.sh:**
Uncomment lines 76-81 in `/scripts/backup-db.sh`:
```bash
if [ -n "$BACKUP_S3_BUCKET" ]; then
    echo -e "${YELLOW}→${NC} Uploading to S3..."
    aws s3 cp "$COMPRESSED_FILE" "s3://$BACKUP_S3_BUCKET/pdfmaster/backups/"
    echo -e "${GREEN}✓${NC} Backup uploaded to S3"
fi
```

### Using Backblaze B2 (Cheaper)

Similar to S3 but more cost-effective for long-term storage.

---

## 8. Monitoring Backup Health

### Check Backup Status

```bash
# List all backups
railway run ls -lh /tmp/backups/

# Check backup age (should be <24h)
railway run find /tmp/backups/ -name "pdfmaster_*.sql.gz" -mtime -1

# Check backup size (should be >1MB)
railway run du -h /tmp/backups/pdfmaster_*.sql.gz
```

### Setup Backup Alerts

**Create Railway Cron Job:**
```bash
# Add to Railway cron (via Dashboard):
# Schedule: 0 3 * * * (daily at 3 AM)
# Command: bash /app/scripts/backup-db.sh && curl -X POST https://your-webhook-url
```

**Use External Monitoring:**
- **Healthchecks.io**: Ping after successful backup
- **Cronitor**: Monitor backup schedule
- **UptimeRobot**: Check backup file exists via HTTP endpoint

---

## 9. Backup Best Practices

### DO ✅
- Run backup before every deployment
- Download critical backups locally
- Test restore procedure monthly
- Monitor backup file sizes (growth = health)
- Keep at least 7 days of backups

### DON'T ❌
- Rely on only automated backups
- Skip backup verification tests
- Store backups only on Railway (single point of failure)
- Delete backups manually without verification
- Restore without confirming backup integrity

---

## 10. Troubleshooting

### "Backup file is empty"

**Cause:** WP-CLI or mysqldump failed

**Fix:**
```bash
# Check if WP-CLI works
railway run wp --info

# Check MySQL connection
railway run wp db check --allow-root

# Try manual mysqldump
railway run mysqldump --host="$MYSQLHOST:$MYSQLPORT" --user="$MYSQLUSER" --password="$MYSQLPASSWORD" "$MYSQL_DATABASE" > backup-manual.sql
```

### "Permission denied" when restoring

**Cause:** Database user lacks privileges

**Fix:**
```bash
# Check Railway MySQL permissions (should be full access)
railway run wp db query "SHOW GRANTS" --allow-root

# Ensure using --allow-root flag
railway run bash /app/scripts/restore-db.sh /tmp/backups/backup.sql.gz --allow-root
```

### "Table already exists" during restore

**Cause:** Partial restore or duplicate tables

**Fix:**
```bash
# Drop all tables before restore
railway run wp db reset --yes --allow-root

# Then restore
railway run bash /app/scripts/restore-db.sh /tmp/backups/backup.sql.gz
```

---

## 11. Backup File Structure

### Backup Naming Convention
```
pdfmaster_YYYYMMDD_HHMMSS.sql.gz
```

Example: `pdfmaster_20251030_143022.sql.gz`
- Created: October 30, 2025 at 14:30:22 UTC

### Backup Contents
- All WordPress tables (wp_*)
- All plugin data
- All user data
- All post/page content
- All settings and options

### Excluded (Not Backed Up)
- Uploaded files (/wp-content/uploads/pdfmaster/)
  - Reason: Auto-deleted after 1 hour by design
- Temporary files (/tmp/)
- Cache files

---

## Quick Reference

**Create Backup:** `railway run bash /app/scripts/backup-db.sh`
**Restore Backup:** `railway run bash /app/scripts/restore-db.sh <file.sql.gz>`
**List Backups:** `railway run ls -lh /tmp/backups/`
**Download Backup:** `railway run cat /tmp/backups/file.sql.gz > local-backup.sql.gz`
**Railway Dashboard:** https://railway.app/ → MySQL → Backups

---

## Status

✅ **Scripts Created:** backup-db.sh, restore-db.sh
⏳ **Pending:** Enable Railway automated backups via dashboard
⏳ **Pending:** Test restore procedure once

**Estimated Setup Time:** 20 minutes
