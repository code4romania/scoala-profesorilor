#!/bin/bash
set -e

# Create .env file from environment variables
echo APP_ENV=$APP_ENV > .env
echo APP_DEBUG=$APP_DEBUG\" >> .env
echo APP_NAME=\"$APP_NAME\" >> .env
echo APP_URL=\"$APP_URL\" >> .env
echo APP_KEY=$APP_KEY >> .env
# echo DB_CONNECTION=$DB_CONNECTION >> .env
# echo DB_HOST=$DB_HOST >> .env
# echo DB_PORT=$DB_PORT >> .env
# echo DB_DATABASE=\"$DB_DATABASE\" >> .env
# echo DB_USERNAME=\"$DB_USERNAME\" >> .env
# echo DB_PASSWORD=\"$DB_PASSWORD\" >> .env
echo CACHE_DRIVER=$CACHE_DRIVER >> .env
echo SESSION_DRIVER=$SESSION_DRIVER >> .env
echo SESSION_FILETIME=$SESSION_FILETIME >> .env
echo QUEUE_DRIVER=$QUEUE_DRIVER >> .env
echo MAIL_DRIVER=$MAIL_DRIVER >> .env
echo MAIL_HOST=\"$MAIL_HOST\" >> .env
echo MAIL_PORT=$MAIL_PORT >> .env
echo MAIL_USERNAME=\"$MAIL_USERNAME\" >> .env
echo MAIL_FROM=\"$MAIL_FROM >> .env\" >> .env
echo MAIL_NAME=\"$MAIL_NAME >> .env\" >> .env
echo MAIL_PASSWORD=\"$MAIL_PASSWORD\" >> .env
echo MAIL_ENCRYPTION=$MAIL_ENCRYPTION >> .env
echo CMS_ACTIVE_THEME=\"$CMS_ACTIVE_THEME\" >> .env
echo CMS_ROUTES_CACHE=$CMS_ROUTES_CACHE >> .env
echo CMS_ASSET_CACHE=$CMS_ASSET_CACHE >> .env
echo CMS_LINK_POLICY=$CMS_LINK_POLICY >> .env
echo CMS_ENABLE_CSRF=$CMS_ENABLE_CSRF >> .env

# Change .env ownership
chown www-data:www-data .env

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
