# syntax=docker/dockerfile:1

ARG PHP_IMAGE=ghcr.io/nhsleadership/nhsl-ubuntu-phpv2:8.4-master

# -----------------------------------------------------------------------------
# composer: PHP dependencies.
# vendor/ is platform-independent PHP, so this stage runs on the runner's native
# platform ($BUILDPLATFORM) and is never emulated under QEMU. It uses the same
# base image as the runtime, so Composer's platform checks still match.
# -----------------------------------------------------------------------------
FROM --platform=$BUILDPLATFORM ${PHP_IMAGE} AS composer
SHELL ["/bin/bash", "-exo", "pipefail", "-c"]
USER root
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer-cache
WORKDIR /app

# Lock files first so this layer is reused until dependencies change.
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

# -----------------------------------------------------------------------------
# app: application source, optimised autoloader, and published package assets.
# -----------------------------------------------------------------------------
FROM composer AS app

COPY . .

# storage/framework/views is gitignored, and artisan can't boot without it
# (Blade needs a compiled-view path), so create the storage tree first.
#
# --no-scripts skips Composer's post-autoload-dump hooks (package:discover,
# livewire assets and filament:upgrade), so run the parts we need explicitly.
# filament:assets and livewire:publish write gitignored files into public/
# from the installed vendor packages, so they always match the PHP side.
# profiledashboard's staging broke when these were skipped; keep them.
RUN mkdir -p storage/app/public \
             storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/testing \
             storage/framework/views \
             storage/logs \
             bootstrap/cache && \
    composer dump-autoload --no-dev --optimize --no-scripts && \
    php artisan package:discover --ansi && \
    php artisan filament:assets && \
    php artisan livewire:publish --assets && \
    rm -rf docker && \
    chmod -R u=rwX,g=rwX,o=rX storage bootstrap/cache && \
    date > /build_site_date

# -----------------------------------------------------------------------------
# assets: Vite build. Also native platform; only static files leave this stage.
# -----------------------------------------------------------------------------
FROM --platform=$BUILDPLATFORM node:24.22.3-slim AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources ./resources
# resources/css/filament/dashboard/theme.scss imports Filament's compiled CSS
# from public/, which only exists once filament:assets has run.
COPY --from=app /app/public/css/filament/filament/app.css ./public/css/filament/filament/app.css
RUN npm run build

# -----------------------------------------------------------------------------
# Runtime image: only COPY instructions, so building linux/arm64 on an amd64
# runner needs no emulated RUN steps. No Node.js and no node_modules.
# PHP-FPM, cron and the init scripts all run as nhsla (uid 1000), which owns
# everything under /app, so storage/ and bootstrap/cache are 775 rather than 777.
# -----------------------------------------------------------------------------
FROM ${PHP_IMAGE}

COPY --chown=nhsla:nhsla docker/rootfs/ /
COPY --chown=nhsla:nhsla --chmod=755 docker/rootfs/etc/cont-init.d/03-laravel.sh /etc/cont-init.d/03-laravel.sh
COPY --from=app --chown=nhsla:nhsla /app /app
COPY --from=assets --chown=nhsla:nhsla /app/public/build /app/public/build
COPY --from=app --chown=nhsla:nhsla /build_site_date /nhsla/build_site_date
