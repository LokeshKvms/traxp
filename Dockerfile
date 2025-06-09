# Use the official PHP 8.1 FPM image
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions including PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install PHP dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader

# Uncomment if you want to build frontend assets during build
RUN npm install
RUN npm run prod

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
