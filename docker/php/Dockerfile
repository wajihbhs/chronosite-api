FROM php:8.4-fpm-alpine

# Dépendances système
RUN apk add --no-cache \
    git \
    unzip \
    libpq-dev \
    icu-dev \
    icu-libs \
    libzip-dev \
    zlib-dev \
    oniguruma-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make

# Extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        intl \
        zip \
        mbstring \
        opcache

# Configuration PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]
