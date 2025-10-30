#!/bin/bash
set -e

echo "Starting PDFSpark container..."

# Wait for database to be available
echo "Waiting for database connection..."
until wp db check --allow-root 2>/dev/null; do
  echo "Database not ready yet, waiting..."
  sleep 2
done
echo "Database connection established!"

# Install RankMath SEO plugin on first run
if ! wp plugin is-installed seo-by-rank-math --allow-root 2>/dev/null; then
    echo "Installing RankMath SEO plugin..."
    wp plugin install seo-by-rank-math --activate --allow-root

    echo "Configuring RankMath SEO..."
    wp option update rank_math_wizard_completed 1 --allow-root
    wp option update rank_math_modules '["sitemap","seo-analysis","rich-snippet"]' --format=json --allow-root
    wp rewrite flush --allow-root

    echo "RankMath SEO installed and configured successfully!"
else
    echo "RankMath SEO already installed, skipping..."
fi

echo "Starting FrankenPHP web server..."
# Start FrankenPHP with Caddyfile
exec frankenphp run --config /app/Caddyfile
