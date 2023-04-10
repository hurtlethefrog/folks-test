FROM php:8.0.10-apache

RUN apt-get -y update && apt-get upgrade -y

RUN mkdir -p /usr/share/man/man1

# Install tools && libraries
RUN apt-get -y install --fix-missing apt-utils nano wget vim dialog locales \
    build-essential git curl pdftk libzip-dev zip tcl \
    libssh2-1-dev libssh2-1 mariadb-client \
    libmcrypt-dev libsqlite3-dev libsqlite3-0 \
    zlib1g-dev libicu-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev gettext \
    && rm -rf /var/lib/apt/lists/*

# Composer and NPM
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP Extensions
RUN docker-php-ext-install tokenizer \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pdo_sqlite \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install calendar \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg  \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install gettext

RUN pecl install ssh2-1.3.1 && docker-php-ext-enable ssh2

RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www

# Configure v-host for apache
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Replace ports in config for 8080.
RUN sed -i -e 's!Listen 80!Listen 8080!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!\*:80>!\*:8080>!g' /etc/apache2/sites-available/*.conf

# Enable apache modules
RUN a2enmod rewrite headers

# Create docker user for permissions.
RUN adduser --disabled-password --gecos '' docker-user

# Add missing group and missing user to groups
RUN if [ -z "$(getent group 1000)" ]; then groupadd 1000; fi

# add needed group to users
RUN usermod -a -G docker-user www-data

USER docker-user

EXPOSE 80

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]


