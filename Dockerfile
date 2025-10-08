# Stage 1: Composer dependencies
FROM composer:2 AS composer-builder

WORKDIR /app

# Install required PHP extensions for composer install (Alpine version)
RUN apk add --no-cache icu-dev jpeg-dev freetype-dev libpng-dev \
    && docker-php-ext-install intl exif gd

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

# Copy only necessary vendor files for frontend build
# (optional optimization step)
# RUN rm -rf vendor/filament/notifications vendor/filament/tables # if unneeded
# COPY . .   # uncomment if you need local code for scripts
# Note: keep minimal to save time

# Stage 2: Node build
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --prefer-offline

# Copy full app source
COPY . .

# Copy vendor from composer builder (so Filament CSS is available)
COPY --from=composer-builder /app/vendor ./vendor

# Build assets
RUN npm run build

# Stage 3: PHP-FPM application
FROM php:8.3-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev \
    libjpeg-dev libfreetype6-dev \
    nginx supervisor procps \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis && rm -rf /tmp/pear

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files and install (cached from builder)
COPY composer.json composer.lock ./
COPY --from=composer-builder /app/vendor ./vendor

# Copy application code
COPY . .

# Copy built assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Generate optimized autoloader
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# Copy configuration files
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Create required directories
RUN mkdir -p storage/{logs,framework/{sessions,views,cache},app/public} \
    bootstrap/cache /run/php /var/log/supervisor

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
