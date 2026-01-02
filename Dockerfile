FROM library/php:8.3-cli-alpine
LABEL maintainer="Joan Font <joanfont@gmail.com>"

WORKDIR /app

RUN apk add --no-cache --upgrade icu-dev libzip-dev && \
    apk add --no-cache --upgrade --virtual=.build-deps \
    linux-headers build-base $PHPIZE_DEPS && \
    docker-php-ext-install sockets zip intl pdo_mysql && \
    apk del .build-deps

COPY --from=library/composer:lts /usr/bin/composer /usr/bin/composer
COPY --from=ghcr.io/roadrunner-server/roadrunner:2025.1.4 /usr/bin/rr /usr/local/bin/rr

ARG ENV
ARG UID
ARG GID

RUN addgroup --gid $GID app && \
    adduser --shell /sbin/nologin --disabled-password --no-create-home --home /app --uid $UID --ingroup app app

ADD docker/entrypoint.sh /entrypoint.sh
ADD docker/php.ini /usr/local/etc/php/conf.d/zzz-php.ini

USER app

COPY --chown=app:app . .

RUN sh -c '[ "$ENV" == "prod" ] && composer install --ignore-platform-reqs --prefer-dist --no-dev --no-interaction --no-scripts || exit 0' && \
    sh -c '[ "$ENV" != "prod" ] && composer install --ignore-platform-reqs --prefer-dist --no-interaction --no-scripts || exit 0'

ENTRYPOINT ["/entrypoint.sh"]
