FROM php:7.4-apache
ARG DEBIAN_FRONTEND=noninteractive

RUN a2enmod rewrite headers expires

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git unzip curl ca-certificates \
      build-essential pkg-config \
      libicu-dev \
      libzip-dev zlib1g-dev \
      libxml2-dev \
      libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
      libonig-dev \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      gd intl pdo_mysql mbstring zip xml opcache exif \
    ; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html

