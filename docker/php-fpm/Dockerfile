# php-fpm
FROM php:7.1-fpm

RUN yes | pecl install xdebug
RUN docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm"]

EXPOSE 9000