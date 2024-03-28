# syntax=docker/dockerfile:1
FROM php:apache

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN apt-get update
RUN apt-get -y install nano
RUN apt-get install -y git zlib1g-dev
RUN apt-get install -y libzip-dev && docker-php-ext-install zip
RUN apt-get install -y libgd-dev && docker-php-ext-configure gd && docker-php-ext-install gd
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get install -y libicu-dev
RUN docker-php-ext-install intl

RUN a2enmod rewrite

RUN docker-php-ext-enable opcache
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY config/app.conf /etc/apache2/sites-available/app.conf
# Copier les fichiers de votre projet dans le conteneur
RUN a2ensite app && a2dissite 000-default
COPY api/ /var/www/api/
COPY config/zzz.ini /usr/local/etc/php/conf.d/
COPY react-docker/ /var/www/react-docker/

# run npm install
RUN cd /var/www/api composer install
RUN cd /var/www/react-docker npm install
RUN cd /var/www/react-docker npm start
EXPOSE 80
