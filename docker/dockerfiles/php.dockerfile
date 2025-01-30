FROM php:8.2-fpm-alpine

ARG UID=1000
ARG GID=1000

ENV UID=${UID}
ENV GID=${GID}

RUN delgroup dialout || true

RUN addgroup -g ${GID} laravel
RUN adduser -D -s /bin/sh -G laravel -u ${UID} laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf

RUN apk add --no-cache \
    libpq \
    postgresql-dev \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R laravel:laravel storage bootstrap/cache

CMD ["php-fpm"]
