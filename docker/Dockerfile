FROM ubuntu:16.04

MAINTAINER Cosmin Bosutar

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

# Install system dependencies
RUN apt-get update \
    && apt-get install -y nginx vim curl cron zip unzip git software-properties-common supervisor jq mysql-client-core-5.7 libjpeg-dev libpng-dev libpq-dev libsqlite3-dev libwebp-dev libzip-dev

RUN apt-get update \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.2-fpm php7.2-ctype php7.2-curl php7.2-xml php7.2-fileinfo php7.2-gd \
                          php7.2-json php7.2-mbstring php7.2-mysql php7.2-sqlite3 php7.2-zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir /run/php \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# Add octobercms cron
RUN echo "* * * * * /usr/bin/php /var/www/artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/october-cron \
    && crontab /etc/cron.d/october-cron

# Symlinking Nginx log files to stdout and stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Copy app files
COPY ./bootstrap /var/www/bootstrap
COPY ./config /var/www/config
COPY ./modules /var/www/modules
COPY ./plugins /var/www/plugins
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
COPY ./docker/php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
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
    && find . -type d \( -path './plugins' -or  -path './storage' -or  -path './themes' -or  -path './plugins/*' -or  -path './storage/*' -or  -path './themes/*' \) -exec chmod g+ws {} \;

EXPOSE 443 80

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord"]
