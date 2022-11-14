FROM php:8.1-fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

RUN apk update && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html
COPY composer.json /var/www/html
RUN composer install