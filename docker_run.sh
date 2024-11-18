#!/bin/bash
set -e

cd /var/www
env >> /var/www/.env
php artisan clear-compiled
php artisan config:clear
* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1
crontab cronfile
rm cronfile
cron
php-fpm8.1 -D
nginx -g "daemon off;"
