FROM php:7.2.2-apache
RUN mkdir /var/www/imagen
RUN chown www-data:www-data /var/www/imagen
COPY default_imagen/*.jpeg /var/www/imagen/
RUN docker-php-ext-install mysqli