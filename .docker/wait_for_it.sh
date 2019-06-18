#!/bin/bash
composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist
php artisan key:generate
php artisan migrate
php-fpm