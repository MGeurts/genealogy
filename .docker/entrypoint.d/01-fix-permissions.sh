#!/bin/sh

# Only run if we're root
if [ "$(id -u)" = "0" ]; then
    # Ensure Laravel directories exist
    mkdir -p /var/www/html/storage/logs
    mkdir -p /var/www/html/storage/framework/sessions
    mkdir -p /var/www/html/storage/framework/views
    mkdir -p /var/www/html/storage/framework/cache
    mkdir -p /var/www/html/bootstrap/cache

    # Fix ownership for writable directories
    chown -R www-data:www-data /var/www/html/storage
    chown -R www-data:www-data /var/www/html/bootstrap/cache
    chown www-data:www-data /var/www/html/.env 2>/dev/null || true

    chmod -R 775 /var/www/html/storage
    chmod -R 775 /var/www/html/bootstrap/cache
    chmod 664 /var/www/html/.env 2>/dev/null || true
fi
