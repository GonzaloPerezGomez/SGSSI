FROM php:8.3-apache-bookworm
RUN mkdir /var/www/imagen /var/www/html/logs
RUN chown -R www-data:www-data /var/www/imagen /var/www/html/logs
RUN chmod -R 775 /var/www/imagen /var/www/html/logs
COPY default_imagen/*.jpeg /var/www/imagen/

COPY apache-config /etc/apache2/sites-available/000-default.conf 
RUN a2enmod headers

RUN echo "\nServerSignature Off\nServerTokens Prod\n" >> /etc/apache2/apache2.conf

RUN docker-php-ext-install mysqli