FROM php:7.0.33-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY www.prenotazioni.com/ /var/www/html/www.prenotazioni.com/
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=AT/ST=Vienna/L=Vienna/O=Security/OU=Development/CN=example.com"
RUN a2enmod rewrite
RUN a2ensite default-ssl
RUN a2enmod ssl
EXPOSE 80:80
EXPOSE 443:443