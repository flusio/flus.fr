FROM php:8.2-fpm

ENV COMPOSER_HOME /tmp

RUN apt-get update && \
    apt-get install -y git libicu-dev libzip-dev locales unzip && \
    pecl install xdebug && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) intl zip && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'en_GB.UTF-8 UTF-8' >> /etc/locale.gen && \
    echo 'fr_FR.UTF-8 UTF-8' >> /etc/locale.gen && \
    locale-gen

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer
