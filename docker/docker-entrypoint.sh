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

exec "$@"
