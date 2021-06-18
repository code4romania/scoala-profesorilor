FROM ubuntu:18.04

MAINTAINER genuineq

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

# Install system dependencies
RUN apt-get update \
    && apt-get install -y nginx vim curl cron zip unzip git software-properties-common supervisor \
                          jq mysql-client-core-5.7 libjpeg-dev libpng-dev libpq-dev libsqlite3-dev \
                          libwebp-dev libzip-dev

RUN apt-get update
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php7.3-fpm php7.3-ctype php7.3-curl php7.3-xml php7.3-fileinfo php7.3-gd \
                          php7.3-json php7.3-mbstring php7.3-mysql php7.3-sqlite3 php7.3-zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir /run/php \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# Add octobercms cron
RUN echo "* * * * * root /usr/bin/php /var/www/artisan schedule:run >> /root/cron.log 2>&1" > /etc/cron.d/october-cron \
    && crontab /etc/cron.d/october-cron

# Symlinking Nginx log files to stdout and stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Copy app files
COPY ./bootstrap /var/www/bootstrap
COPY ./config /var/www/config
COPY ./modules /var/www/modules
COPY ./modules/backend/routes.php /var/routes.php
COPY ./plugins /var/www/plugins
COPY ./tests /var/www/tests
COPY ./themes /var/www/themes
COPY ./.babelrc /var/www/.babelrc
COPY ./.htaccess /var/www/.htaccess
COPY ./.jshintrc /var/www/.jshintrc
COPY ./artisan /var/www/artisan
COPY ./composer.json /var/www/composer.json
COPY ./composer.lock /var/www/composer.lock
COPY ./package.json /var/www/package.json
COPY ./phpcs.xml /var/www/phpcs.xml
COPY ./phpunit.xml /var/www/phpunit.xml
COPY ./server.php /var/www/server.php
COPY ./index.php /var/www/index.php

# Create the storage folder and the rest of the storage folder structure
RUN mkdir /var/www/storage \
    && mkdir /var/www/storage/temp \
    && mkdir /var/www/storage/temp/public \
    && mkdir /var/www/storage/temp/media \
    && mkdir /var/www/storage/logs \
    && mkdir /var/www/storage/cms \
    && mkdir /var/www/storage/cms/cache \
    && mkdir /var/www/storage/cms/combiner \
    && mkdir /var/www/storage/cms/twig \
    && mkdir /var/www/storage/framework \
    && mkdir /var/www/storage/framework/cache \
    && mkdir /var/www/storage/framework/sessions \
    && mkdir /var/www/storage/framework/views

# Copy storage relevant folders & files
COPY ./storage/static /var/www/storage/static

# Copy nginx configuration
COPY ./docker/default /etc/nginx/sites-available/default
# Copy php fpm configuration
COPY ./docker/php-fpm.conf /etc/php/7.3/fpm/php-fpm.conf
# Copy php configuration
COPY ./docker/php.ini /etc/php/7.3/fpm/php.ini
# Copy supervisord configuration
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint script
COPY ./docker/docker-entrypoint.sh /usr/local/bin/
# Backwards compatibility
RUN chmod 777 /usr/local/bin/docker-entrypoint.sh \
     && ln -s /usr/local/bin/docker-entrypoint.sh /

# Set working directory
WORKDIR /var/www

#Install project dependencies
RUN composer install --no-interaction --no-dev --prefer-dist --no-scripts \
    && composer dump-autoload -o \
    && composer clearcache \
    && chown -R www-data:www-data /var/www \
    && find . -type d \( -path './plugins' -or  -path './storage' -or  -path './themes' -or  -path './plugins/*' -or  -path './storage/*' -or  -path './themes/*'  -or  -path './plugins/*/*' -or  -path './storage/*/*' -or  -path './themes/*/*' \) -exec chmod g+ws {} \;

EXPOSE 443 80

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord"]
