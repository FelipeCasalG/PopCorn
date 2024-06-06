FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer.json and composer.lock to the container
COPY composer*.json composer*.lock ./

# Install vendor packages
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application code
COPY . .

# Run Composer scripts (if any)
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative
