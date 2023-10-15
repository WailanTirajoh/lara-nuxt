# Use PHP 8.1 with Alpine Linux as the base image
FROM php:8.1-fpm-alpine

# Install system dependencies and PHP extensions
# RUN apk add --no-cache \
#     libpng-dev \
#     libjpeg-turbo-dev \
#     freetype-dev

# RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Set the working directory
# WORKDIR /app

# # Copy the Laravel application into the container
# COPY . .

# # Install PHP dependencies using Composer
# RUN composer install
