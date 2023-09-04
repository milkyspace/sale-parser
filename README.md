# sale-parser
Scan the links to discounts and send them to telegram

Quick start:

chown -R www-data:www-data database/
chmod 777 -R database

./vendor/bin/sail up

./vendor/bin/sail artisan schedule:work
