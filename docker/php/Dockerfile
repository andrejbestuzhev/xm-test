FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        curl \
        libpq-dev \
        git \
        openssh-client \
        libzip-dev zip unzip \
	&& docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pdo \
    && docker-php-ext-configure pdo_pgsql \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure exif \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        sockets \
        pdo \
        pdo_pgsql \
        opcache \
        bcmath \
        pcntl \
        exif \
        zip \
        gd \
    && pecl install xdebug && docker-php-ext-enable xdebug


COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY ./docker/php/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/
COPY ./docker/php/entrypoint.sh /entrypoint.sh


WORKDIR /app

COPY . /app

RUN set -xe \
    && chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

RUN set -xe \
    && composer install --no-interaction --no-cache --no-dev

EXPOSE 9000

CMD []
