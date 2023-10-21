FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    autoconf \
    build-base \
    linux-headers

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql sockets exif

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug