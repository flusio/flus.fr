FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y libicu-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) intl
