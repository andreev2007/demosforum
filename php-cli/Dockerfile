# ARG PHP_BASE_IMAGE_VERSION
# FROM php:${PHP_BASE_IMAGE_VERSION}

FROM php:7.4-cli

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.4-pgsql php7.4-gd php-redis \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
