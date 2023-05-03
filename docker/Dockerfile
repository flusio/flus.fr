FROM php:8.2-fpm

RUN apt-get update && \
    apt-get install -y libicu-dev locales && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) intl

RUN echo 'en_GB.UTF-8 UTF-8' >> /etc/locale.gen && \
    echo 'fr_FR.UTF-8 UTF-8' >> /etc/locale.gen && \
    locale-gen
