FROM php:8.3-apache

RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install intl

RUN a2enmod rewrite

COPY . /var/www/html

WORKDIR /var/www/html

# Use 777 for storage for debugging (change to 775 and www-data later for production)
RUN mkdir -p /var/www/html/storage \
    && touch /var/www/html/storage/emaildata.txt \
    && touch /var/www/html/storage/phonedata.txt \
    && touch /var/www/html/storage/imagedata.txt \
    && touch /var/www/html/storage/history.txt \
    && chmod -R 777 /var/www/html/storage

EXPOSE 80