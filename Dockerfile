# Use the official PHP image with Alpine Linux
FROM php:8.0-cli-alpine

# Install dependencies
RUN apk --no-cache add \
    postgresql-client \
    redis \
    libpq \
    bash \
    git \
    && apk add --no-cache --virtual .build-deps \
    libxml2-dev \
    zlib-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apk del .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application files
COPY . .

# Install PHP dependencies
RUN composer install

# Expose port 8000
EXPOSE 8000

# Command to run the PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
