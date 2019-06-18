# FROM composer as composer
# COPY . /app/
# RUN composer install \
#     --ignore-platform-reqs \
#     --no-interaction \
#     --no-plugins \
#     --no-scripts \
#     --prefer-dist

# FROM node:10-alpine as node
# COPY . /app/
# WORKDIR /app
# RUN npm install

FROM php:7.3.6-fpm-alpine3.9
#RUN pecl install swoole \ 
#    && docker-php-ext-enable swoole
RUN apk add --no-cache openssl bash mysql-client nodejs npm dos2unix
RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz

WORKDIR /var/www
RUN rm -rf /var/www/html
RUN ln -s public html

EXPOSE 9000
ENTRYPOINT [ "php-fpm" ]
