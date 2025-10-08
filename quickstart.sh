#!/bin/bash
set -e

echo "=========================================="
echo "  Genealogy Docker Quick Start"
echo "=========================================="
echo ""

# Stop containers
echo "[1/10] Stopping containers..."
docker-compose down 2>/dev/null || true

# Setup .env
echo "[2/10] Setting up .env..."
if [ ! -f .env ]; then
    cp .env.docker .env
    sed -i.bak 's/DB_HOST=localhost/DB_HOST=db/' .env
    sed -i.bak 's/REDIS_HOST=localhost/REDIS_HOST=redis/' .env
    sed -i.bak 's/APP_FORCE_HTTPS=true/APP_FORCE_HTTPS=false/' .env
    rm -f .env.bak
fi

# Create directories
echo "[3/10] Creating directories..."
mkdir -p docker/{nginx,php,mysql,supervisor}
mkdir -p storage/{logs,framework/{sessions,views,cache},app/public}
mkdir -p bootstrap/cache

# Set permissions
echo "[4/10] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || sudo chmod -R 775 storage bootstrap/cache

# Build
echo "[5/10] Building containers (this takes time)..."
docker-compose build --no-cache

# Start
echo "[6/10] Starting containers..."
docker-compose up -d

# Wait for services
echo "[7/10] Waiting for services..."
sleep 20

# Generate key
echo "[8/10] Generating APP_KEY..."
docker-compose exec -T app php artisan key:generate --force

# Setup
echo "[9/10] Running setup..."
docker-compose exec -T app php artisan storage:link
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Fix permissions
echo "[10/10] Fixing permissions..."
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "=========================================="
echo "  Setup Complete!"
echo "=========================================="
echo ""
echo "Application: http://localhost:8000"
echo "Login: administrator@genealogy.test / password"
echo ""
