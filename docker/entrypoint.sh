#!/bin/bash
set -e
cd /var/www/html

# .env: create from example on first run
if [ ! -f .env ]; then
    cp .env.example .env
    echo "[entrypoint] created .env from .env.example"
fi

# vendor/ is missing when the source tree is bind-mounted over the image
if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] vendor/ missing - running composer install (first run takes a few minutes)"
    composer install --prefer-dist --no-interaction --no-progress
fi

# Laravel needs these writable
mkdir -p storage/framework/{cache/data,sessions,testing,views} storage/logs storage/app/public bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# APP_KEY
if ! grep -qE '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --no-interaction
fi

# Wait for the database
echo "[entrypoint] waiting for database ${DB_HOST:-db}:${DB_PORT:-3306} ..."
for i in $(seq 1 60); do
    if php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); exit(0);} catch (Exception $e) { exit(1);} '; then
        echo "[entrypoint] database is up"
        break
    fi
    sleep 2
done

# public/storage symlink for uploaded files
[ -L public/storage ] || php artisan storage:link --no-interaction || true

# Clear stale caches (config paths differ from the original Windows host)
php artisan config:clear --no-interaction || true
php artisan view:clear --no-interaction || true

# Optional: run migrations automatically (set RUN_MIGRATIONS=true in docker-compose)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

exec "$@"
