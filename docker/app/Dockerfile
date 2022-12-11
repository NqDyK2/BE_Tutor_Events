FROM php:8.1.2-fpm-alpine

RUN apk update && apk add make openssh autoconf
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

ENV PHPREDIS_VERSION 3.0.0

RUN mkdir -p /usr/src/php/ext/redis
RUN curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1
RUN echo 'redis' >> /usr/src/php-available-exts
# RUN docker-php-ext-install redis
# RUN pecl install redis && docker-php-ext-enable redis

RUN apk add --no-cache openssl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app