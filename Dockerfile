# Use the official PHP 8.1 image as the base image
FROM php:8.1-fpm-alpine

# Install necessary packages and dependencies
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    autoconf \
    build-base \
    linux-headers

# Configure and install additional PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql sockets exif

RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pcntl

# Expose the PCOV port for coverage data collection
EXPOSE 9000

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
