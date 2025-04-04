#!/bin/bash

# Update dari git
git pull

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan optimize

# Migrate database (--force untuk menjalankan di production)
php artisan migrate --force

# Setup storage link jika belum
php artisan storage:link

# Setup permission
chmod -R 775 storage bootstrap/cache

echo "Deployment completed successfully!"