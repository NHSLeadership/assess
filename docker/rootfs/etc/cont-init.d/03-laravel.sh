#!/command/with-contenv bash

sed -i 's|session.save_path = "tcp://redis:6379"|session.save_path = "tcp://dragonfly:6379"|' /nhsla/etc/php.ini

php artisan livewire:publish --assets
php artisan storage:link