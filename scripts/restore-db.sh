#!/bin/bash
#
# Database Restore Script for PDFSpark
# Usage: railway run bash /app/scripts/restore-db.sh <backup-file.sql.gz>
#

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if backup file provided
if [ -z "$1" ]; then
    echo -e "${RED}Error:${NC} No backup file specified"
    echo ""
    echo "Usage: railway run bash /app/scripts/restore-db.sh <backup-file.sql.gz>"
    echo ""
    echo "Available backups:"
    ls -lh /tmp/backups/pdfmaster_*.sql.gz 2>/dev/null || echo "  No backups found in /tmp/backups/"
    exit 1
fi

BACKUP_FILE="$1"

echo -e "${YELLOW}PDFSpark Database Restore${NC}"
echo "========================================"
echo "Date: $(date)"
echo "Backup file: $BACKUP_FILE"
echo ""

# Check if file exists
if [ ! -f "$BACKUP_FILE" ]; then
    echo -e "${RED}✗${NC} Backup file not found: $BACKUP_FILE"
    exit 1
fi

# Confirm restore (safety check)
echo -e "${RED}⚠ WARNING:${NC} This will overwrite the current database!"
echo "Type 'yes' to continue: "
read -r CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Restore cancelled."
    exit 0
fi

# Decompress if gzipped
if [[ "$BACKUP_FILE" == *.gz ]]; then
    echo -e "${YELLOW}→${NC} Decompressing backup..."
    TEMP_SQL="/tmp/restore_temp.sql"
    gunzip -c "$BACKUP_FILE" > "$TEMP_SQL"
    RESTORE_FILE="$TEMP_SQL"
    echo -e "${GREEN}✓${NC} Backup decompressed"
else
    RESTORE_FILE="$BACKUP_FILE"
fi

# Restore database using WP-CLI
echo -e "${YELLOW}→${NC} Restoring database..."
echo "  This may take a few minutes..."

if command -v wp &> /dev/null; then
    wp db import "$RESTORE_FILE" --allow-root
    echo -e "${GREEN}✓${NC} Database restored successfully via WP-CLI"
else
    echo -e "${YELLOW}→${NC} WP-CLI not found. Using mysql..."

    # Fallback to mysql if WP-CLI not available
    MYSQL_HOST="${MYSQLHOST}:${MYSQLPORT}"
    mysql \
        --host="$MYSQL_HOST" \
        --user="$MYSQLUSER" \
        --password="$MYSQLPASSWORD" \
        "$MYSQL_DATABASE" \
        < "$RESTORE_FILE"

    echo -e "${GREEN}✓${NC} Database restored successfully via mysql"
fi

# Clean up temporary file
if [ -f "$TEMP_SQL" ]; then
    rm "$TEMP_SQL"
    echo -e "${GREEN}✓${NC} Temporary files cleaned up"
fi

# Verify restore
echo -e "${YELLOW}→${NC} Verifying restore..."

if command -v wp &> /dev/null; then
    TABLE_COUNT=$(wp db query "SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema='$MYSQL_DATABASE'" --allow-root --skip-column-names)
    echo -e "${GREEN}✓${NC} Restore verified"
    echo "  Tables found: $TABLE_COUNT"
else
    echo -e "${YELLOW}⚠${NC} Verification skipped (WP-CLI not available)"
fi

# Flush cache
if command -v wp &> /dev/null; then
    echo -e "${YELLOW}→${NC} Flushing WordPress cache..."
    wp cache flush --allow-root
    echo -e "${GREEN}✓${NC} Cache flushed"
fi

echo ""
echo "========================================"
echo -e "${GREEN}Database restored successfully!${NC}"
echo ""
echo "Next steps:"
echo "1. Test site: https://www.pdfspark.app/"
echo "2. Test login: https://www.pdfspark.app/wp-admin"
echo "3. Test payment flow: Upload → Process → Pay → Download"
echo ""
