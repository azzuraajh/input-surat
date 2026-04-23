#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

set_env_var() {
    key="$1"
    value="$2"

    if [ -z "$value" ]; then
        return
    fi

    escaped_value=$(printf '%s' "$value" | sed 's/[\/&]/\\&/g')

    if grep -q "^${key}=" .env; then
        sed -i "s/^${key}=.*/${key}=${escaped_value}/" .env
    else
        printf '\n%s=%s\n' "$key" "$value" >> .env
    fi
}

set_env_var APP_NAME "\"${APP_NAME}\""
set_env_var APP_ENV "${APP_ENV}"
set_env_var APP_DEBUG "${APP_DEBUG}"
set_env_var APP_URL "${APP_URL}"
set_env_var APP_TIMEZONE "${APP_TIMEZONE}"
set_env_var APP_LOCALE "${APP_LOCALE}"
set_env_var APP_FALLBACK_LOCALE "${APP_FALLBACK_LOCALE}"
set_env_var APP_FAKER_LOCALE "${APP_FAKER_LOCALE}"
set_env_var DB_CONNECTION "${DB_CONNECTION}"
set_env_var DB_HOST "${DB_HOST}"
set_env_var DB_PORT "${DB_PORT}"
set_env_var DB_DATABASE "${DB_DATABASE}"
set_env_var DB_USERNAME "${DB_USERNAME}"
set_env_var DB_PASSWORD "${DB_PASSWORD}"
set_env_var SESSION_DRIVER "${SESSION_DRIVER}"
set_env_var CACHE_STORE "${CACHE_STORE}"
set_env_var QUEUE_CONNECTION "${QUEUE_CONNECTION}"
set_env_var ADMIN_NAME "${ADMIN_NAME}"
set_env_var ADMIN_EMAIL "${ADMIN_EMAIL}"
set_env_var ADMIN_PASSWORD "${ADMIN_PASSWORD}"

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --no-interaction
fi

php artisan optimize:clear --no-interaction >/dev/null 2>&1 || true

attempts=0

until php artisan migrate --force --no-interaction; do
    attempts=$((attempts + 1))

    if [ "$attempts" -ge 10 ]; then
        echo "Gagal terkoneksi ke database setelah beberapa percobaan."
        exit 1
    fi

    echo "Menunggu database siap..."
    sleep 3
done

php artisan db:seed --class=AdminUserSeeder --force --no-interaction

exec php artisan serve --host=0.0.0.0 --port=8000
