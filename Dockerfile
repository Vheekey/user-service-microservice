FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    librdkafka-dev \
    && docker-php-ext-install pdo_mysql zip\
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy files from application
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
