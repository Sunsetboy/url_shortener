FROM php:8.2-fpm-buster

RUN apt-get update && apt-get install -y \
    libc-client-dev libkrb5-dev \
    curl \
    wget \
    git \
    iputils-ping \
    mariadb-server \
    libxslt-dev \
    libicu-dev \
    libmcrypt-dev \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install opcache \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

# RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug


RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

EXPOSE 9000 8000
RUN groupadd -g 1000 -r developer && useradd -r -m -u 1000 -g developer developer

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

USER developer
WORKDIR /var/www/app_symfony/public_html

CMD ["symfony", "serve"]