FROM php:8.2.7-alpine

WORKDIR /var/www/api

COPY . .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

RUN php composer.phar install

CMD [ "php", "-S", "0.0.0.0:8001", "-t", "./public" ]