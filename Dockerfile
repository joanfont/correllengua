FROM library/php:8.4-cli-alpine
LABEL maintainer="Joan Font <joanfont@gmail.com>"

WORKDIR /app

RUN apk add --no-cache --upgrade icu-dev libpq-dev libzip-dev && \
    apk add --no-cache --upgrade --virtual=.build-deps \
    linux-headers build-base $PHPIZE_DEPS && \
    docker-php-ext-install sockets zip intl pdo_mysql && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    apk del .build-deps

COPY --from=library/composer:lts /usr/bin/composer /usr/bin/composer
COPY --from=ghcr.io/roadrunner-server/roadrunner:2025.1.4 /usr/bin/rr /usr/local/bin/rr

ARG ENV
ARG UID
ARG GID

RUN addgroup --gid $GID app && \
    adduser --shell /sbin/nologin --disabled-password --no-create-home --home /app --uid $UID --ingroup app app

USER app

COPY --chown=app:app . .

RUN sh -c '[ "$ENV" == "prod" ] && composer install --ignore-platform-reqs --prefer-dist --no-dev --no-interaction || exit 0'
RUN sh -c '[ "$ENV" != "prod" ] && composer install --ignore-platform-reqs --prefer-dist --no-interaction || exit 0'

ADD docker/entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
