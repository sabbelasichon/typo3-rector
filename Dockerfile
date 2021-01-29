FROM composer:2 as composer

COPY . .

RUN composer install \
        --classmap-authoritative \
        --ignore-platform-reqs \
        --no-ansi \
        --no-dev \
        --no-interaction \
        --no-progress

FROM php:7-alpine as builder

RUN set -eux; \
        apk add --no-cache --virtual .build-deps \
            icu-dev; \
        docker-php-ext-install -j$(nproc) \
            intl

FROM php:7-alpine

LABEL org.opencontainers.image.source="https://github.com/sabbelasichon/typo3-rector"

COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20190902/intl.so /usr/local/lib/php/extensions/no-debug-non-zts-20190902/intl.so

COPY --from=composer /app /opt/typo3-rector

ENV PATH="/opt/typo3-rector/bin:$PATH"

RUN set -eux; \
        runDeps="$( \
            scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
                | tr ',' '\n' \
                | sort -u \
                | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
        )"; \
        apk add --no-cache --virtual .phpext-rundeps $runDeps

RUN set -eux; \
        docker-php-ext-enable \
            intl \
            opcache; \
        { \
            echo 'opcache.enable=1'; \
            echo 'opcache.enable_cli=1'; \
            echo 'opcache.file_cache=/tmp/opcache'; \
            echo 'opcache.file_update_protection=0'; \
            echo 'opcache.interned_strings_buffer=8'; \
            echo 'opcache.max_accelerated_files=4000'; \
            echo 'opcache.memory_consumption=128'; \
            echo 'opcache.revalidate_freq=60'; \
        } >> $PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini

RUN apk add --no-cache tini

RUN set -eux; \
        mkdir /tmp/opcache; \
        php --version; \
        typo3-rector list; \
        \
        chmod 777 -R /tmp

WORKDIR /app

ARG TYPO3_RECTOR_VERSION=unknown
ARG TYPO3_RECTOR_VERSION_HASH=unknown
ENV TYPO3_RECTOR_VERSION=${TYPO3_RECTOR_VERSION} \
    TYPO3_RECTOR_VERSION_HASH=${TYPO3_RECTOR_VERSION_HASH}

ENTRYPOINT ["/opt/typo3-rector/docker-entrypoint.sh"]

CMD ["typo3-rector"]
