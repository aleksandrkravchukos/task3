FROM php:7.4-cli

MAINTAINER Aleksandr Kravchuk <aleksandr.kravchuk.os@gmail.com>


RUN apt-get update \
    && apt-get install -y curl libfreetype6-dev libmcrypt-dev libzip-dev git libonig-dev argon2 libargon2-0 libargon2-0-dev \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install iconv zip opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PHPREDIS_VERSION 3.0.0

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN pecl install -o -f redis  \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]