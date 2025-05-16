# Use the official PHP CLI image
FROM php:8.2-cli

# set working directory
WORKDIR /app

# install system deps and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# copy composer files & install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# copy the rest of the app
COPY . .

# make the `run` binary executable
RUN chmod +x run

# default entrypoint: forward all args to your CLI
ENTRYPOINT ["php", "/app/run"]
# show help if no command given
CMD ["--help"]
