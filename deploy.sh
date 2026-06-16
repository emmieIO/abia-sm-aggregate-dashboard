#!/bin/bash
set -e
# 1. Pull latest code
git pull origin main

# 2. Allow Composer to run as root
export COMPOSER_ALLOW_SUPERUSER=1
# Ensure app goes back up if anything fails
trap "php artisan up" EXIT

echo "🚀 Starting deployment..."

# 1. Enter maintenance mode
php artisan down --retry=60 || true

# 2. Update dependencies
composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build

# 3. Database migrations and seeding
php artisan migrate --force
php artisan db:seed --force

# 4. Clear and rebuild caches
php artisan optimize
php artisan view:cache
php artisan event:cache
php artisan storage:link --force

# 5. Grant appropriate permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache

# Ensure log file exists and is writable
touch storage/logs/laravel.log
chmod 664 storage/logs/laravel.log

echo "✅ Deployment complete!"

