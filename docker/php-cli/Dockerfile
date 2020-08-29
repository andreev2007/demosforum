# ARG PHP_BASE_IMAGE_VERSION
# FROM php:${PHP_BASE_IMAGE_VERSION}

FROM php:7.1-cli

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client \
    && docker-php-ext-install mcrypt pdo_mysql

RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev

RUN docker-php-ext-install mbstring

RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
    --enable-gd-native-ttf

RUN docker-php-ext-install gd



WORKDIR "/var/www/html"