FROM php:8.3-apache-bookworm
WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY index.php public/index.php

RUN apt-get update && \
        apt-get -y install snmp libsnmp-dev

RUN docker-php-ext-install snmp

EXPOSE 80