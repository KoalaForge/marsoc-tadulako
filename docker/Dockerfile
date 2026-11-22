# syntax=docker/dockerfile:1.7
###############################################################################
# M.R.T web — production image (FrankenPHP + supervisor: web + queue worker)
#
#   base       PHP 8.5 runtime + extensions (shared by every stage)
#   vendor     composer install --no-dev
#   assets     Vite build (Filament's theme CSS imports from vendor/, so the
#              Node stage needs vendor/ from the previous stage)
#   production final runtime image
###############################################################################

# ── base ─────────────────────────────────────────────────────────────────────
FROM dunglas/frankenphp:1-php8.5-bookworm AS base

ENV DEBIAN_FRONTEND=noninteractive \
    TZ=Asia/Jakarta

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        supervisor \
        curl \
        ca-certificates \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/*

# mongodb is PINNED to 1.21.x to match composer.lock (mongodb/mongodb 1.21.x needs
# ext-mongodb ^1.21; the unpinned latest ext is 2.x and breaks `composer install`).
# mongodb: database | redis: cache/session/queue | pdo_sqlite: medialibrary DB
# gd (+webp): image conversions | intl/zip/bcmath/exif: Filament + medialibrary
RUN install-php-extensions \
        mongodb-1.21.2 \
        redis \
        pdo_sqlite \
        gd \
        exif \
        intl \
        zip \
        bcmath \
        pcntl \
        opcache

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/99-production.ini"

# opcache.file_cache must exist before ANY php/composer CLI call below.
RUN mkdir -p /tmp/opcache-file-cache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── vendor ───────────────────────────────────────────────────────────────────
FROM base AS vendor

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# ── assets ───────────────────────────────────────────────────────────────────
FROM node:22-slim AS assets

WORKDIR /app

COPY package.json package-lock.json .npmrc ./
RUN npm ci

COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ── production ───────────────────────────────────────────────────────────────
FROM base AS production

LABEL org.opencontainers.image.source="https://github.com/koalaforge/marsoc-tadulako-be"

ENV APP_ENV=production \
    APP_DEBUG=false

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

COPY docker/Caddyfile        /etc/caddy/Caddyfile
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/start-container  /usr/local/bin/start-container
COPY docker/healthcheck      /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck \
    && sed -i 's/\r$//' /usr/local/bin/start-container /usr/local/bin/healthcheck

# Build-time artisan work needs a throw-away .env (no secrets, no DB); it is
# DELETED before the layer ends so only the container's real environment
# applies at runtime — a baked .env would silently supply defaults for any
# variable the server forgot to set (e.g. QUEUE_CONNECTION=sync).
RUN printf "APP_NAME=MRT\nAPP_ENV=production\nAPP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\nAPP_DEBUG=false\nDB_CONNECTION=mongodb\nCACHE_STORE=file\nSESSION_DRIVER=file\nQUEUE_CONNECTION=sync\n" > .env \
    && composer dump-autoload --optimize --classmap-authoritative --no-dev --no-scripts \
    && php artisan package:discover --ansi --quiet \
    && php artisan filament:assets --quiet \
    && php artisan filament:optimize --quiet \
    && php artisan event:cache --quiet \
    && rm -f .env /usr/bin/composer

# Runtime directories (the storage volume mounted over /app/storage starts
# empty, so start-container recreates these too).
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/testing \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
        /var/log/supervisor \
    && chmod -R 775 storage bootstrap/cache

# Not needed at runtime.
RUN rm -rf tests docker/proxy .github .env.example phpunit.xml package.json package-lock.json vite.config.ts

# 8080 = FrankenPHP HTTP (TLS is terminated by the shared caddy-proxy)
EXPOSE 8080

ENTRYPOINT []
CMD ["/usr/local/bin/start-container"]
