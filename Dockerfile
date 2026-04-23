FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libsqlite3-dev \
        libonig-dev \
        libzip-dev \
        libxml2-dev \
        default-mysql-client \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        bcmath \
        gd \
        zip \
        dom \
        simplexml \
        xml \
        xmlreader \
        xmlwriter \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && chmod +x docker/start-app.sh \
    && mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

EXPOSE 8000

CMD ["/var/www/html/docker/start-app.sh"]
