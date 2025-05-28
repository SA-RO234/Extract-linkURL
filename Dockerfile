FROM php:8.3-apache

# Install intl extension
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy files into the container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Ensure storage directory and text files exist with correct permissions
RUN mkdir -p /var/www/html/storage \
    && touch /var/www/html/storage/emaildata.txt \
    && touch /var/www/html/storage/phonedata.txt \
    && touch /var/www/html/storage/imagedata.txt \
    && touch /var/www/html/storage/history.txt \
    && chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage

# Expose port 80
EXPOSE 80
