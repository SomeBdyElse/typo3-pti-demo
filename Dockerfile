FROM alpine:3.29 as cms-builder

RUN \
    apk add --update --no-cache \
        bash \
        git \
        openssl \
        wget \
        patch \
        rsync \
        \
        php83 \
        php83-json \
        php83-phar \
        php83-iconv \
        php83-curl \
        php83-openssl \
        php83-mbstring

# PHP composer libiconv patch https://github.com/docker-library/php/issues/240
RUN apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Install composer
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/70527179915d55b3811bebaec55926afd331091b/web/installer -O - -q | php -- --version="2.5.8" --quiet --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1
ENV COMPOSER_EXIT_ON_PATCH_FAILURE 1

WORKDIR /app
CMD ["/bin/bash", "-c", "composer install"]






FROM alpine:3.20.3 AS cms-webserver

# Install common tools
# gettext    provides envsubst, used for fixture import to db
RUN apk add --update --no-cache \
    openssl \
    bash \
    wget \
    rsync \
    unzip \
    curl \
    gettext

# Install supervisor to run nginx and php-fpm at the same time
RUN apk add --update --no-cache \
    supervisor

# Install mysql client for TYPO3 console
RUN apk add --update --no-cache \
    mysql-client

# Install imagemagick for TYPO3 image manipulation
RUN apk add --update --no-cache \
    imagemagick
ENV TYPO3_GFX_PROCESSOR_PATH=/usr/bin/
ENV TYPO3_GFX_PROCESSOR_PATH_LZW=/usr/bin/

# Install php and modules
RUN \
    apk add --update --no-cache \
        php83-cli \
        php83-fpm \
        \
        php83-curl \
        php83-dom \
        php83-exif \
        php83-fileinfo \
        php83-gd \
        php83-intl \
        php83-json \
        php83-mbstring \
        php83-mysqli \
        php83-opcache \
        php83-pdo \
        php83-redis \
        php83-simplexml \
        php83-soap \
        php83-tokenizer \
        php83-xdebug \
        php83-xml \
        php83-xmlwriter \
        php83-zip


# Configure PHP
COPY docker/web/php/xdebug.ini /etc/php83/conf.d/50_xdebug.ini
COPY docker/web/php/typo3.ini /etc/php83/conf.d/99_typo3.ini

# Install nginx
RUN apk add --update --no-cache \
    nginx
RUN mkdir -p /run/nginx

# Configure nginx
COPY docker/web/nginx/default.conf.template /etc/nginx/http.d/default.conf.template
RUN echo "clear_env = no" >> /etc/php83/php-fpm.d/www.conf
COPY docker/web/certs/nginx.pem /etc/ssl/certs/nginx.pem
COPY docker/web/certs/nginx.key /etc/ssl/private/nginx.key

# Accept own https certificate
RUN cp /etc/ssl/certs/nginx.pem /usr/local/share/ca-certificates/nginx.pem
RUN update-ca-certificates

# Configure scheduler cronjob
RUN echo "*/1 * * * * /app/public/typo3/sysext/core/bin/typo3 scheduler:run" | crontab -

# Configure supervisor to run cron (solr index), nginx and php-fpm at the same time
ADD docker/web/supervisor.ini /etc/supervisor.d/supervisor.ini
ADD docker/web/startup.sh /startup.sh
CMD chmod +x /startup.sh
CMD ["/bin/bash", "/startup.sh"]

WORKDIR /app
EXPOSE 80
EXPOSE 443
