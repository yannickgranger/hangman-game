ARG PHP_VERSION=8.3
ARG INSTALL_DIR=app

FROM alpine:3.19 as app-builder
ARG APP_ENV
ARG APP_DIR

WORKDIR /srv/app

COPY behat.yml.dist composer.json composer.lock symfony.lock .env ./
COPY bin ./bin
COPY config ./config
COPY features ./features
COPY public ./public
COPY src ./src
COPY tests ./tests
COPY translations ./translations

FROM php:${PHP_VERSION}-fpm-alpine AS app_php
ARG TZ
ENV TZ=${TZ}

# fpm config
COPY .docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY .docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
COPY .docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY .docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN SHELL=/bin/ash apk add --no-cache --update \
    "linux-headers=6.6-r0" \
    "acl=2.3.2-r0" \
    "fcgi=2.4.2-r4" \
    "file=5.45-r1" \
    "gettext=0.22.5-r0" \
    "icu-dev=74.2-r0" \
    "git=2.45.1-r0" \
    "libzip-dev=1.10.1-r0" \
    "tzdata=2024a-r1";

RUN SHELL=/bin/ash \
    set -eux; \
	install-php-extensions mbstring http intl json pdo_pgsql zip opcache redis amqp;


COPY --from=app-builder /srv /srv

WORKDIR /srv/app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD ["docker-healthcheck"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer

RUN ln -s "$PHP_INI_DIR"/php.ini-production "$PHP_INI_DIR"/php.ini ; \
    set -eux; \
    rm -rf var/cache var/log /composer ; \
    mkdir -p var/cache var/log /composer; \
	composer install --prefer-dist --no-progress --no-scripts --no-interaction; \
	composer dump-autoload --classmap-authoritative; \
	composer run-script post-install-cmd; \
	chmod +x bin/console /usr/local/bin/docker-healthcheck /usr/local/bin/docker-entrypoint.sh; \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && apk del tzdata; \
    sync

VOLUME /srv/app/var /composer

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]

FROM app_php as app_php_dev

RUN install-php-extensions xdebug \
    && usermod -u 1000 www-data # change to your UID to solve file permissions issues in dev \
    && 	composer symfony:dump-env dev;


FROM nginx:1.20-alpine AS app_nginx
ARG NGINX_CONF_DIR
WORKDIR /srv/app

COPY --from=app-builder /srv/app/public /srv/app/public
COPY ${NGINX_CONF_DIR}/conf.d/default.conf /etc/nginx/conf.d/default.conf
