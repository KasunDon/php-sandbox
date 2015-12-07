FROM php:5.5-apache

# Install all dependencies
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && apt-get install -qqy \
    zlib1g-dev libicu-dev libssl-dev libmcrypt-dev supervisor git-core && \
    apt-get clean && rm -r /var/lib/apt/lists/* && \
    docker-php-ext-install intl mbstring zip mcrypt && \
    yes '' | pecl install apc && \
    yes '' | pecl install mongo && \
    echo "extension=apc.so" >> /usr/local/etc/php/conf.d/apc.ini && \
    echo "extension=mongo.so" >> /usr/local/etc/php/conf.d/mongo.ini

# Environment Variables
ENV APP_ENV production

RUN mkdir -p /root/.composer/ && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY docker/setup/auth.json /root/.composer/auth.json

# Configure Apache
RUN a2enmod rewrite && \
    a2enmod setenvif && \
    usermod -aG root www-data

# Copy source code in
COPY docker/setup/sandbox.conf /etc/apache2/sites-enabled/sandbox.conf
COPY docker/setup/apache2.conf /etc/apache2/apache2.conf
COPY docker/setup/supervisord.conf /etc/supervisor/supervisord.conf

# Default Datetime
RUN echo "date.timezone=Europe/London" >> /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html/sandbox

COPY . /var/www/html/sandbox/

RUN composer install

EXPOSE 80

CMD ["/usr/bin/supervisord"]
