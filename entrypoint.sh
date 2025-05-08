#!/bin/sh

set -e

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --no-interaction
fi

php-fpm -D
exec nginx -g "daemon off;"
