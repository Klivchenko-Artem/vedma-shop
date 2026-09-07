#!/bin/sh
set -e

cd /var/www

# Первый запуск: своего .env нет — берём пример и генерируем ключ
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --force
fi

# Ждём, пока база примет подключение
echo "Ждём базу данных..."
until php -r "new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 2
done
echo "База готова."

php artisan migrate --force --seed
php artisan storage:link || true
php artisan config:cache

exec "$@"
