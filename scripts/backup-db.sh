#!/bin/bash
#
# Database Backup Script for PDFSpark
# Run via Railway CLI: railway run bash /app/scripts/backup-db.sh
#

set -e  # Exit on error

# Configuration
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/tmp/backups"
BACKUP_FILE="$BACKUP_DIR/pdfmaster_$DATE.sql"
RETENTION_DAYS=7

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}PDFSpark Database Backup${NC}"
echo "========================================"
echo "Date: $(date)"
echo ""

# Create backup directory
mkdir -p "$BACKUP_DIR"
echo -e "${GREEN}✓${NC} Backup directory created: $BACKUP_DIR"

# Export database using WP-CLI
echo -e "${YELLOW}→${NC} Exporting database..."

if command -v wp &> /dev/null; then
    wp db export "$BACKUP_FILE" --allow-root
    echo -e "${GREEN}✓${NC} Database exported successfully"
else
    echo -e "${RED}✗${NC} WP-CLI not found. Falling back to mysqldump..."

    # Fallback to mysqldump if WP-CLI not available
    MYSQL_HOST="${MYSQLHOST}:${MYSQLPORT}"
    mysqldump \
        --host="$MYSQL_HOST" \
        --user="$MYSQLUSER" \
        --password="$MYSQLPASSWORD" \
        "$MYSQL_DATABASE" \
        > "$BACKUP_FILE"

    echo -e "${GREEN}✓${NC} Database exported via mysqldump"
fi

# Check if backup file exists and is not empty
if [ -s "$BACKUP_FILE" ]; then
    SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    echo -e "${GREEN}✓${NC} Backup file created: $BACKUP_FILE"
    echo "  Size: $SIZE"
else
    echo -e "${RED}✗${NC} Backup file is empty or not created"
    exit 1
fi

# Compress backup
echo -e "${YELLOW}→${NC} Compressing backup..."
gzip "$BACKUP_FILE"
COMPRESSED_FILE="${BACKUP_FILE}.gz"
COMPRESSED_SIZE=$(du -h "$COMPRESSED_FILE" | cut -f1)
echo -e "${GREEN}✓${NC} Backup compressed: ${COMPRESSED_FILE}"
echo "  Compressed size: $COMPRESSED_SIZE"

# Clean up old backups (keep last 7 days)
echo -e "${YELLOW}→${NC} Cleaning up old backups..."
find "$BACKUP_DIR" -name "pdfmaster_*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete
REMAINING=$(find "$BACKUP_DIR" -name "pdfmaster_*.sql.gz" -type f | wc -l)
echo -e "${GREEN}✓${NC} Old backups cleaned (keeping last $RETENTION_DAYS days)"
echo "  Backups remaining: $REMAINING"

# Optional: Upload to external storage (S3, Backblaze, etc.)
# Uncomment and configure if using external storage:
#
# if [ -n "$BACKUP_S3_BUCKET" ]; then
#     echo -e "${YELLOW}→${NC} Uploading to S3..."
#     aws s3 cp "$COMPRESSED_FILE" "s3://$BACKUP_S3_BUCKET/pdfmaster/backups/"
#     echo -e "${GREEN}✓${NC} Backup uploaded to S3"
# fi

echo ""
echo "========================================"
echo -e "${GREEN}Backup completed successfully!${NC}"
echo ""
echo "Backup location: $COMPRESSED_FILE"
echo "To restore: railway run bash /app/scripts/restore-db.sh $COMPRESSED_FILE"
echo ""
