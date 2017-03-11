# webapp container

FROM php:5.5-apache

RUN apt-get update && apt-get install -y git nano 


# apt-get cant install PHP extentions. 
# PHP image provides docker-php-ext-install:
RUN docker-php-ext-install mysql pdo_mysql

# Get rid of timezone errors in page content
ADD php.ini /usr/local/etc/php/php.ini



RUN a2enmod rewrite

WORKDIR /var/www/html

RUN git clone https://github.com/jenca-cloud/bimportal-php .

# Overwrite database config
# mysql credentials here are static for the moment
ADD database.php /var/www/html/application/config/database.php


