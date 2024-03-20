FROM php:8.1-apache

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli

RUN mkdir -p /var/www/uploads
RUN chown www-data /var/www/uploads/
RUN chmod 770 /var/www/uploads/