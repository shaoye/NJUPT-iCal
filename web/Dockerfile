FROM php:7-apache

RUN apt-get update && apt-get install zip -y

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install