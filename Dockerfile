FROM php:7.2-apache

# enable rewrite engine for .htaccess files
RUN a2enmod rewrite

# install mysqli module
RUN docker-php-ext-install mysqli

# fixes permissions issue when writing files to a mounted directory
# note that the new UID for the user `www-data` has to be the same
# as the UID of the user that owns in the mounted directory on the host machine
ARG uid=1000
RUN usermod -u $uid www-data


