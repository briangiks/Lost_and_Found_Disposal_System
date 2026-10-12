#!/usr/bin/env bash
set -e

echo "Clearing any stale cached config from the build..."
php artisan config:clear

echo "Running database migrations..."
php artisan migrate --force

echo "Seeding demo data (safe to re-run — uses updateOrCreate, never duplicates)..."
php artisan db:seed --force

echo "Caching config/routes/views for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking public storage (item photos)..."
php artisan storage:link || true

echo "Starting nginx + php-fpm..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf