FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application files
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize

# Make run script executable
RUN chmod +x run
