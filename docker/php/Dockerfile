FROM php:8.2-fpm

RUN apt-get update

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY conf.d/* $PHP_INI_DIR/conf.d

WORKDIR /var/www/public

ENTRYPOINT [ "php-fpm" ]