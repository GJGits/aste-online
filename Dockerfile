FROM php:7.0.33-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY www.prenotazioni.com/ /var/www/html/www.prenotazioni.com/
EXPOSE 80:80