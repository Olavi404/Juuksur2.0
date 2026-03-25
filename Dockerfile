FROM php:8.3-cli

WORKDIR /opt/render/project/src

# System dependencies + PostgreSQL driver build deps + Node.js/npm for Vite build
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        libpq-dev \
        nodejs \
        npm \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy full app
COPY . .

# Install PHP/JS dependencies and build frontend assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build

# On free tier we run migrate+seed at boot to avoid unsupported preDeployCommand
CMD sh -c "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host 0.0.0.0 --port ${PORT:-10000}"
