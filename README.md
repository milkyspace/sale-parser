# sale-parser
Scan the links to discounts and send them to telegram

Quick start:

chown -R www-data:www-data database/
chmod 777 -R database

./vendor/bin/sail up

./vendor/bin/sail artisan schedule:work

## FIXES
### Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use
1. sudo lsof -i:8080
2. kill *ID*
3. ./vendor/bin/sail down
4. ./vendor/bin/sail up
