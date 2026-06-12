FROM php:8.3-alpine

RUN apk add --update --no-cache \
    $PHPIZE_DEPS linux-headers \
    libmemcached-dev zlib-dev cyrus-sasl-dev

RUN pecl install xdebug memcached pcov \
    && docker-php-ext-enable xdebug memcached pcov
