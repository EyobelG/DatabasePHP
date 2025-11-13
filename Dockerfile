# Use official PHP image with Apache
FROM php:8.2-apache

# Enable mysqli extension
RUN docker-php-ext-install mysqli

# Copy all project files to Apache's root directory
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
