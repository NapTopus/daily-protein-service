#!/bin/sh

cd /var/www/html

if [ -z "$(grep ^APP_KEY= .env | cut -d= -f2)" ]; then
    echo "APP_KEY is empty, generating...";
    php artisan key:generate --force
fi

php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate

exec "$@"