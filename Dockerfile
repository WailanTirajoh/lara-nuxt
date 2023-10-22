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

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer