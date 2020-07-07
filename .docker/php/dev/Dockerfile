FROM bulbulatory:1.0.0

USER root

# hadolint ignore=SC2086
RUN apk --no-cache add --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

USER app:app

WORKDIR /var/www/bulbulatory
