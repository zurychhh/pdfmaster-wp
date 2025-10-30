FROM dunglas/frankenphp:php8.3

# Install required PHP extensions for WordPress
RUN install-php-extensions \
    mysqli \
    pdo_mysql \
    gd \
    imagick \
    zip \
    opcache \
    intl \
    exif

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy all WordPress files to /app
COPY . /app

# Set working directory
WORKDIR /app

# Install Stripe PHP SDK for payments plugin
RUN if [ -f wp-content/plugins/pdfmaster-payments/composer.json ]; then \
        cd wp-content/plugins/pdfmaster-payments && \
        composer install --no-dev --optimize-autoloader --no-interaction; \
    fi

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp

# Install & configure RankMath SEO plugin
RUN wp plugin install seo-by-rank-math --activate --allow-root && \
    wp option update rank_math_wizard_completed 1 --allow-root && \
    wp option update rank_math_modules '["sitemap","seo-analysis","rich-snippet"]' --format=json --allow-root && \
    wp rewrite flush --allow-root

# Port is configured via $PORT env var in Caddyfile

# Start FrankenPHP with Caddyfile
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
