FROM php:8.2-fpm-alpine as app

RUN apk update \
    && docker-php-ext-install pdo pdo_mysql

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY composer.* .

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

COPY . .

RUN composer dump-autoload --optimize

FROM app as app_dev

COPY ./docker/php/conf.d/xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

RUN apk add --update linux-headers \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps
