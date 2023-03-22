#!/bin/bash
set -e
echo "Deployment started ..."
(php artisan down) || true
composer install --no-dev --no-interaction --prefer-dist
php artisan optimize:clear
php artisan optimize
php artisan migrate
php artisan up
echo "Deployment finished!"
