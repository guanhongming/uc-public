FROM php:8.2-fpm-alpine AS builder

WORKDIR /var/www/html

RUN apk add --no-cache \
    git curl unzip libzip-dev oniguruma-dev libxml2-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    nodejs npm

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY package.json package-lock.json ./
RUN npm install
COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN npm run build

RUN php artisan config:cache
RUN php artisan route:cache

RUN chown -R www-data:www-data storage bootstrap/cache

FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache libzip libpng libjpeg-turbo freetype oniguruma libxml2

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=builder /var/www/html/. .

EXPOSE 9000

CMD ["php-fpm"]
