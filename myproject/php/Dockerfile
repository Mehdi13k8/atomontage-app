FROM php:7.4-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy the PHP scripts into the container
COPY src/ /var/www/html/
