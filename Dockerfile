FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev iputils-ping \
    zip \
    unzip \
    libzip-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mysqli pdo_mysql pdo gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install
