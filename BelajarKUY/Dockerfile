# syntax=docker/dockerfile:1.7
# =============================================================================
# BelajarKUY — Multi-stage build
#   stage "assets"  : build React/Inertia + Tailwind bundle with Vite (Node)
#   stage "vendor"  : install PHP dependencies (Composer, no-dev)
#   target "app"    : PHP 8.5-FPM runtime (web, queue, scheduler, reverb)
#   target "web"    : Nginx image with the compiled public/ baked in
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1 — Frontend assets (Vite build -> public/build)
# -----------------------------------------------------------------------------
FROM node:22-alpine AS assets
WORKDIR /app

# VITE_* values are inlined at build time. Override via compose build args.
# Before SSL: port 80 / http. After SSL: rebuild with port 443 / https.
ARG VITE_APP_NAME=BelajarKUY
ARG VITE_REVERB_APP_KEY=belajarkuy-key
ARG VITE_REVERB_HOST=belajarkuy.site
ARG VITE_REVERB_PORT=80
ARG VITE_REVERB_SCHEME=http

COPY package.json package-lock.json* ./
RUN npm ci

COPY . .
RUN printf 'VITE_APP_NAME=%s\nVITE_REVERB_APP_KEY=%s\nVITE_REVERB_HOST=%s\nVITE_REVERB_PORT=%s\nVITE_REVERB_SCHEME=%s\n' \
    "$VITE_APP_NAME" "$VITE_REVERB_APP_KEY" "$VITE_REVERB_HOST" "$VITE_REVERB_PORT" "$VITE_REVERB_SCHEME" > .env \
 && npm run build

# -----------------------------------------------------------------------------
# Stage 2 — PHP dependencies (Composer, production only)
# -----------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --prefer-dist --no-interaction --no-progress \
    --no-scripts --no-autoloader --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts --ignore-platform-reqs

# -----------------------------------------------------------------------------
# Target "app" — PHP 8.5-FPM runtime
# -----------------------------------------------------------------------------
FROM php:8.5-fpm-bookworm AS app
WORKDIR /var/www/html

# Pull the extension installer (handles all system libs automatically).
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        intl \
        zip \
        exif \
        pcntl \
        opcache \
        redis \
 && apt-get update \
 && apt-get install -y --no-install-recommends default-mysql-client gosu \
 && rm -rf /var/lib/apt/lists/*

# PHP / OPcache / FPM production tuning.
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-belajarkuy.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Application source + compiled dependencies + built assets.
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# storage symlink, runtime dirs, package discovery, permissions.
RUN rm -rf public/storage \
 && ln -s /var/www/html/storage/app/public public/storage \
 && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
 && php artisan package:discover --ansi || true \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Entrypoint (strip CRLF in case it was checked out on Windows).
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint && chmod +x /usr/local/bin/entrypoint

EXPOSE 9000
ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]

# -----------------------------------------------------------------------------
# Target "web" — Nginx serving the compiled public/ (assets baked in)
# -----------------------------------------------------------------------------
FROM nginx:1.27-alpine AS web
COPY docker/nginx/conf.d/app.conf /etc/nginx/conf.d/default.conf
# Bring in public/ (index.php, build/, images, storage symlink) from the app image.
COPY --from=app /var/www/html/public /var/www/html/public
EXPOSE 80 443
