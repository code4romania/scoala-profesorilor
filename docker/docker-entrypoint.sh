#!/bin/bash
set -e

# Cache application routes
php artisan route:clear

# Clear configuration cache
php artisan config:clear

# Create the public folder
php artisan october:mirror public --relative

# Run any DB migrations
#php artisan october:up

# Reinstall plugins
php artisan plugin:refresh genuineq.user
php artisan plugin:refresh genuineq.tms

# Start cron
service cron start

exec "$@"
