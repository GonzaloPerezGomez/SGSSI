FROM php:7.2.2-apache
RUN mkdir /var/www/imagen
RUN chown -R www-data:www-data /var/www/imagen
RUN chmod -R 775 /var/www/imagen
COPY default_imagen/*.jpeg /var/www/imagen/
RUN docker-php-ext-install mysqli