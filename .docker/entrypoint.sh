#!/bin/bash

npm config set cache /var/www/.npm-cache --global

cd /var/www/frontend && npm install && cd ..

cd backend

cp .env.example .env
cp .env.testing.example .env.testing
composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist
php artisan key:generate
php artisan migrate
php-fpm