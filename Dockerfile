FROM composer as composer
COPY . /app
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM node:10-alpine as node
COPY . /app
WORKDIR /app
RUN npm install

FROM php:fpm-alpine
#RUN pecl install swoole \ 
#    && docker-php-ext-enable swoole
RUN apk add bash mysql-client
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

EXPOSE 9000
ENTRYPOINT [ "php-fpm" ]
