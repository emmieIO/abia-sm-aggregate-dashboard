#!/bin/bash
set -e

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

    echo "✅ Deployment complete!"
