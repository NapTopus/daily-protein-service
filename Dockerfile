# === 1. Builder：安裝依賴 ===
FROM composer:2.8.12 AS vendor

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-progress --no-scripts --no-interaction

COPY . .
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader

# === 2. Runtime：真正要跑的 PHP-FPM ===

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath

WORKDIR /var/www/html

COPY --from=vendor /app ./

RUN chown -R www-data:www-data storage bootstrap/cache

RUN docker-php-ext-install opcache

USER www-data

CMD [ "php-fpm" ]

