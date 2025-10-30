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

# Copy and set up entrypoint script (installs RankMath at runtime)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Port is configured via $PORT env var in Caddyfile

# Use entrypoint to install RankMath on startup
ENTRYPOINT ["docker-entrypoint.sh"]
