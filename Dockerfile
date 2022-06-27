FROM php:8.1-apache

RUN apt-get update && apt-get install -y zip unzip libpq-dev git nano curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql pdo_pgsql zip exif pcntl gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

COPY ./apache2/conf.d/ /etc/apache2/sites-available/

COPY ./ /var/www/html

RUN a2enmod rewrite
RUN a2ensite app.conf

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

WORKDIR /var/www/html
