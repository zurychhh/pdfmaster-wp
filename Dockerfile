FROM dunglas/frankenphp:php8.4

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

# Port is configured via $PORT env var in Caddyfile

# Start FrankenPHP with Caddyfile
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
