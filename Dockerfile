FROM php:5.5-apache

RUN apt-get update && apt-get install -y git nano

# PHP image provides docker-php-ext-install:
RUN docker-php-ext-install mysql pdo_mysql

# Remove timezone errors in page content
ADD php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite

WORKDIR /var/www/html

RUN git clone https://github.com/jencahq/theBIMportal .

# Overwrite database config
ADD database.php /var/www/html/application/config/database.php
