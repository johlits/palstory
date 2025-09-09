# Web image for Palstory: PHP 8.2 + Apache with app files baked in
FROM php:8.2-apache-bullseye

# Surpresses debconf complaints of trying to install apt packages interactively
ARG DEBIAN_FRONTEND=noninteractive

# Install dependencies and PHP extensions minimal set
RUN apt-get -y update --fix-missing \
    && apt-get upgrade -y \
    && apt-get --no-install-recommends install -y \
       default-mysql-client \
       zlib1g-dev \
       libzip-dev \
       libicu-dev \
       libonig-dev \
       curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    zip \
    intl \
    mbstring

# Enable Apache modules
RUN a2enmod rewrite

# Copy application (static HTML/PHP) into web root
WORKDIR /var/www/html
COPY palstory-game/src/html/ /var/www/html/

# Set secure default permissions (adjust as needed)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Expose the default HTTP port
EXPOSE 80

# Apache is the default CMD from php:apache base image
