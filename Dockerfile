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

# Check if Xdebug is installed and install it if not
RUN if ! pecl list | grep -q 'xdebug'; then \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug; \
    fi

# Configure and install additional PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql sockets exif

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy your custom php.ini and Xdebug configuration
COPY ./docker/php/conf.d/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./docker/php/conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

# Expose PHP-FPM port
EXPOSE 9000

# Define the working directory for PHP
WORKDIR /var/www/html

# Start the PHP-FPM service
CMD ["php-fpm"]
