# PHP Dockerfile
FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y libpq-dev libpng-dev libzip-dev zip
RUN docker-php-ext-install pgsql pdo_pgsql gd zip bcmath \
&& docker-php-ext-enable opcache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY --chown=www-data:www-data  asiayo-pretest /var/www/html/

RUN composer install  && rm /usr/local/bin/composer

RUN php artisan optimize
