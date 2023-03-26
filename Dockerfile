FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

COPY . /var/www/html

COPY apache-config.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80
