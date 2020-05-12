FROM php:7.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y cron git-core jq unzip vim zip libjpeg-dev libpng-dev libpq-dev libsqlite3-dev libwebp-dev libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-configure gd --with-png-dir --with-jpeg-dir --with-webp-dir && \
    docker-php-ext-install exif gd mysqli opcache pdo_pgsql pdo_mysql zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add octobercms cron
RUN echo "* * * * * /var/www/artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/october-cron && \
    crontab /etc/cron.d/october-cron

COPY ./bootstrap /var/www/bootstrap
COPY ./config /var/www/config
COPY ./modules /var/www/modules
COPY ./plugins /var/www/plugins
COPY ./storage /var/www/storage
COPY ./tests /var/www/tests
COPY ./themes /var/www/themes
COPY ./.babelrc /var/www/.babelrc
COPY ./.htaccess /var/www/.htaccess
COPY ./.jshintrc /var/www/.jshintrc
COPY ./artisan /var/www/artisan
COPY ./composer.json /var/www/composer.json
COPY ./package.json /var/www/package.json
COPY ./phpcs.xml /var/www/phpcs.xml
COPY ./phpunit.xml /var/www/phpunit.xml
COPY ./server.php /var/www/server.php

# Set working directory
WORKDIR /var/www

#Install project dependencies
RUN composer install --no-interaction --prefer-dist --no-scripts && \
    composer clearcache && \
    chown -R www-data:www-data /var/www && \
    find . -type d \( -path './plugins' -or  -path './storage' -or  -path './themes' -or  -path './plugins/*' -or  -path './storage/*' -or  -path './themes/*' \) -exec chmod g+ws {} \;
