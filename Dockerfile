# Use an official PHP-FPM image as the base. Alpine is lightweight.
FROM php:8.2-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies required by Laravel and Vue
# - PHP extensions for database, XML, etc.
# - Node.js, npm for building frontend assets
# - Git, unzip for Composer
RUN apk add --no-cache \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    nodejs \
    npm

# Install PHP extensions using docker-php-ext-install helper
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application source code to the container
COPY . .

# Install Composer dependencies
# This is run here to cache the vendor directory if composer.json doesn't change
RUN composer install --optimize-autoloader 
# Install NPM dependencies and build the frontend for production
RUN npm install
RUN npm run build

# Set correct permissions for storage and cache so the web server can write to them
# The 'www-data' user is the default user for php-fpm
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 to listen for PHP-FPM requests
EXPOSE 9000

# The command to run when the container starts
CMD ["php-fpm"]
