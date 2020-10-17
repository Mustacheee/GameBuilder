# php-fpm
FROM php:7.4-fpm
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip

# Setup debugging
ENV PHP_EXTENSION_XDEBUG 1
#COPY ./docker/php-fpm/php.ini /usr/local/etc/php/php.ini
RUN yes | pecl install xdebug
RUN docker-php-ext-install pdo pdo_mysql

# Install dependencies
RUN apt-get install -y git zip unzip

WORKDIR /temp
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm composer-setup.php

WORKDIR /var/www
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install

# Expose port
EXPOSE 9000

# Begin FPM service
CMD ["php-fpm"]