FROM php:7.2.2-apache
RUN mkdir /var/www/imagen
RUN chown www-data:www-data /var/www/imagen
COPY app/image/nacidos-de-la-bruma.jpeg /var/www/imagen/nacidos-de-la-bruma.jpeg
RUN docker-php-ext-install mysqli