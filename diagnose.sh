#!/bin/bash

echo "=========================================="
echo "  Docker Diagnostics"
echo "=========================================="
echo ""

# Check containers
echo "1. Container Status:"
docker-compose ps
echo ""

# Check .env
echo "2. Critical .env Settings:"
grep "APP_KEY=" .env 2>/dev/null || echo "  ✗ No APP_KEY"
grep "DB_HOST=" .env 2>/dev/null
grep "REDIS_HOST=" .env 2>/dev/null
grep "APP_FORCE_HTTPS=" .env 2>/dev/null
echo ""

# Check services
echo "3. Service Health:"
echo -n "  Database: "
docker-compose exec -T db mysqladmin ping -h localhost -u root -prootsecret 2>&1 | grep -q "mysqld is alive" && echo "✓ OK" || echo "✗ FAILED"

echo -n "  Redis: "
docker-compose exec -T redis redis-cli ping 2>&1 | grep -q "PONG" && echo "✓ OK" || echo "✗ FAILED"

echo -n "  Application: "
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    echo "✓ OK (HTTP $HTTP_CODE)"
else
    echo "✗ HTTP $HTTP_CODE"
fi
echo ""

# Check logs
echo "4. Recent Laravel Errors:"
docker-compose exec -T app tail -20 storage/logs/laravel.log 2>/dev/null || echo "  No log file found"
echo ""

echo "=========================================="
echo "Quick Fix Commands:"
echo "=========================================="
echo "docker-compose exec app php artisan key:generate"
echo "docker-compose exec app php artisan migrate --force"
echo "docker-compose exec app php artisan config:cache"
echo ""
