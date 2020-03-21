FROM php:7.2-apache
RUN a2enmod rewrite
WORKDIR /var/www/
# copy apache config
COPY ./apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# copy extra php ini files
COPY ./php.ini /usr/local/etc/php/conf.d/docker_php.ini

# install caching + debug
RUN pecl install apcu
#RUN pecl install xdebug --- disabled because of performance issues
RUN docker-php-ext-enable apcu opcache
#RUN docker-php-ext-enable xdebug --- disabled because of performance issues

# install other PHP extensions
RUN apt-get update \
    && apt-get -y install zip unzip libpng-dev libjpeg-dev libxml2-dev
RUN docker-php-ext-install gd mysqli pdo_mysql gettext xmlrpc soap


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY ./composer.json ./composer.json

RUN mkdir -p public/plugins && mkdir -p public/mu-plugins
RUN chown www-data:www-data public/plugins
RUN chown www-data:www-data public/mu-plugins

RUN composer install


COPY ./.htaccess ./public/.htaccess




