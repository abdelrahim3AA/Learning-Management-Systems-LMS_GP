FROM composer:2 AS composer

WORKDIR /app
COPY . /app
RUN composer install --no-dev --optimize-autoloader --no-scripts

FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        exif \
        zip \
        intl \
        gd \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=composer /app /var/www/html

COPY nginx.conf /etc/nginx/http.d/default.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
