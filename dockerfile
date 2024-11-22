FROM php:8.2-alpine

# Installing system dependencies
RUN apk update && apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    mysql-client \
    wget \
    autoconf \
    build-base \
    linux-headers \
    mongodb-tools

# Installing PHP extensions
RUN docker-php-ext-install \
    intl \
    zip \
    pdo_mysql

# Installing the Mongo extension
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
RUN git config --global --add safe.directory /app