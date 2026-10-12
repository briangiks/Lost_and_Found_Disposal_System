# Builds a single container running php-fpm + nginx (via supervisor) — the
# simplest way to deploy this Laravel app to a Docker-based host like Render,
# Fly.io, Railway, or any VPS. See README.md "Deploying" for the full guide.

FROM php:8.3-fpm-bookworm

# System packages + the PHP extensions Laravel/this app needs.
# pdo_pgsql -> Postgres (Render's free database), pdo_mysql -> MySQL (XAMPP/most
# shared hosts), gd -> image handling for item photo uploads.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl unzip nginx supervisor \
        libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libwebp-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo pdo_pgsql pdo_mysql mbstring zip exif pcntl gd bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# --no-dev: production dependencies only (no PHPUnit etc. in the image).
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

RUN mkdir -p storage/framework/{cache,sessions,views} storage/app/public storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]