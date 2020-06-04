#!/bin/bash
set -e

# Create .env file from environment variables
echo APP_ENV=$APP_ENV > .env
echo APP_DEBUG=$APP_DEBUG >> .env
echo APP_LOG=$APP_LOG >> .env
echo APP_NAME=\"$APP_NAME\" >> .env
echo APP_URL=\"$APP_URL\" >> .env
echo APP_KEY=$APP_KEY >> .env
echo DB_HOST=\"$DB_HOST\" >> .env
echo DB_PORT=$DB_PORT >> .env
echo DB_DATABASE=\"$DB_DATABASE\" >> .env
echo DB_USERNAME=\"$DB_USERNAME\" >> .env
echo DB_PASSWORD=\"$DB_PASSWORD\" >> .env
echo CACHE_DRIVER=$CACHE_DRIVER >> .env
echo SESSION_DRIVER=$SESSION_DRIVER >> .env
echo SESSION_LIFETIME=$SESSION_LIFETIME >> .env
echo QUEUE_DRIVER=$QUEUE_DRIVER >> .env
echo MAIL_DRIVER=$MAIL_DRIVER >> .env
echo MAIL_HOST=\"$MAIL_HOST\" >> .env
echo MAIL_PORT=$MAIL_PORT >> .env
echo MAIL_USERNAME=\"$MAIL_USERNAME\" >> .env
echo MAIL_NAME=\"$MAIL_NAME\" >> .env >> .env
echo MAIL_PASSWORD=\"$MAIL_PASSWORD\" >> .env
echo MAIL_ENCRYPTION=$MAIL_ENCRYPTION >> .env
echo CMS_BACKEND_FORCE_SECURE=$CMS_BACKEND_FORCE_SECURE >> .env
echo CMS_ASSET_DEEP_HASHING=$CMS_ASSET_DEEP_HASHING >> .env
echo CMS_EDGE_UPDATES=$CMS_EDGE_UPDATES >> .env
echo CMS_ASSET_MINIFY=$CMS_ASSET_MINIFY >> .env
echo CMS_BACKEND_URI=\"$CMS_BACKEND_URI\" >> .env
echo CMS_BACKEND_FORCE_REMEMBER=$CMS_BACKEND_FORCE_REMEMBER >> .env
echo CMS_CONVERT_LINE_ENDINGS=$CMS_CONVERT_LINE_ENDINGS >> .env
echo CMS_ACTIVE_THEME=\"$CMS_ACTIVE_THEME\" >> .env
echo CMS_ROUTES_CACHE=$CMS_ROUTES_CACHE >> .env
echo CMS_ASSET_CACHE=$CMS_ASSET_CACHE >> .env
echo CMS_LINK_POLICY=$CMS_LINK_POLICY >> .env
echo CMS_ENABLE_CSRF=$CMS_ENABLE_CSRF >> .env

# Run any DB migrations
php artisan october:up

# Clear cache for application routes
php artisan route:clear

# Clear and cache application configuration
php artisan config:clear
# php artisan config:cache

# Create the public folder
php artisan october:mirror public --relative

# Change public folder ownership
chown -R www-data:www-data /var/www

# Start cron
service cron start

exec "$@"
