#!/command/with-contenv bash

sed -i 's|session.save_path = "tcp://redis:6379"|session.save_path = "tcp://dragonfly:6379"|' /nhsla/etc/php.ini

mkdir -p /app-shared/storage/framework/{cache,testing,views,sessions}

cd /app-shared
php artisan storage:link
sleep $((RANDOM % 10))
php artisan optimize
