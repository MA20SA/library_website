FROM php:8.1-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy all your files into the web server directory
COPY . /var/www/html/

EXPOSE 80
