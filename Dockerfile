# FROM php:8.1-fpm

# RUN apt-get update && apt-get install -y \
#     zlib1g-dev \
#     g++ \
#     git \
#     libicu-dev \
#     zip \
#     libzip-dev \
#     libpq-dev \
#     && docker-php-ext-install intl opcache pdo pdo_pgsql \
#     && pecl install apcu \
#     && docker-php-ext-enable apcu pdo_pgsql \
#     && docker-php-ext-configure zip \
#     && docker-php-ext-install zip

# WORKDIR /var/www/html/Studi/Projet-ECF/Zoo-Arcadia-Back

# COPY . .

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN curl -sS https://get.symfony.com/cli/installer | bash
# RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# RUN git config --global user.email "fxd15130@gamil.com" \
#     && git config --global user.name "francois-xavier-darragon"

# RUN composer install --no-scripts --no-autoloader
# RUN composer dump-autoload --optimize

# RUN chown -R www-data:www-data .

# CMD ["php-fpm"]

FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    g++ \
    git \
    libicu-dev \
    zip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install intl opcache pdo pdo_pgsql \
    && pecl install apcu \
    && docker-php-ext-enable apcu pdo_pgsql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/html/Studi/Projet-ECF/Zoo-Arcadia-Back

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN git config --global user.email "fxd15130@gamil.com" \
    && git config --global user.name "francois-xavier-darragon"

# Les fichiers doivent être montés via Docker Compose, pas copiés
# RUN composer install --no-scripts --no-autoloader
# RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data .

CMD ["php-fpm"]
