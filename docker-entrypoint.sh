#!/bin/bash
set -e

# Wait a moment for database to be ready
sleep 2

# Run Laravel setup commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Seed database (optional - comment out if not needed)
php artisan db:seed --force || true

# Execute the CMD
exec "$@"
