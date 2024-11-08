FROM php:7.2.2-apache
RUN mkdir /var/www/imagen /var/www/logs
RUN chown -R www-data:www-data /var/www/imagen /var/www/logs
RUN chmod -R 775 /var/www/imagen /var/www/logs
COPY default_imagen/*.jpeg /var/www/imagen/
RUN docker-php-ext-install mysqli