FROM php:8.3-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash icu-dev
RUN apk add --update linux-headers
RUN docker-php-ext-install bcmath
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Source code
WORKDIR /var/www/travel-expense-tracker

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]
