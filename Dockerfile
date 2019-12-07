FROM php:7.2-apache

# enable rewrite engine for .htaccess files
RUN a2enmod rewrite

# install mysqli module
RUN docker-php-ext-install mysqli

