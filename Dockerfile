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
