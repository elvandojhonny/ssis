FROM php:8.5-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install -j1 gd
RUN docker-php-ext-install -j1 pdo_mysql
RUN docker-php-ext-install -j1 zip
RUN docker-php-ext-install -j1 intl
RUN docker-php-ext-install -j1 bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

RUN rm -f public/hot

RUN npm ci

RUN npm run build

RUN php artisan package:discover --ansi

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan optimize && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}