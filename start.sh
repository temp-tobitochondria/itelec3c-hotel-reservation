#!/usr/bin/env bash

# Run Laravel migrations
php artisan migrate --force

# Start PHP built-in server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
