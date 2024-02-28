#!/usr/bin/env sh

set -e

APP_DIR="${APP_DIR:-/app}";
cd $APP_DIR && composer install && php artisan migrate --seed --force --no-interaction && php artisan serve --host 0.0.0.0
