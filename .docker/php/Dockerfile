FROM php:7.2.21-fpm-alpine3.10 AS bulbulatory_php

# hadolint ignore=SC2086
RUN apk --no-cache add --virtual .build-deps $PHPIZE_DEPS \
    && apk --no-cache add --virtual .ext-deps \
    libmcrypt-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    msmtp \
    curl-dev \
    pkgconfig \
    openssl-dev \
    zlib-dev \
    icu-dev \
    g++ \
    libxslt-dev \
    busybox-suid

RUN docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
                                    --with-png-dir=/usr/include/ \
                                    --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    opcache \
    pcntl \
    bcmath \
    sockets \
    xmlrpc \
    gd \
    intl \
    xsl \
    soap \
    zip \
    && docker-php-source delete \
    && apk del .build-deps

RUN addgroup -g 1000 -S app && adduser -G app -u 1000 -h /var/www -s /bin/bash -S app

SHELL ["/bin/ash", "-eo", "pipefail", "-c"]
RUN	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
  && composer global require hirak/prestissimo

RUN curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
  && chmod +x mhsendmail_linux_amd64 \
  && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN chown -R app:app /var/www
USER app:app

WORKDIR /var/www/bulbulatory
