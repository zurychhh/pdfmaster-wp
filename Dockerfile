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

# Copy all WordPress files to /app
COPY . /app

# Set working directory
WORKDIR /app

# Port is configured via $PORT env var in Caddyfile

# Start FrankenPHP with Caddyfile
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
