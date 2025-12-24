#!/usr/bin/env bash
# exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build assets
npm ci
npm run build

# Clear and cache Laravel configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Seed the database (optional - comment out if you don't want to seed on every deploy)
php artisan db:seed --force
