# sale-parser
Scan the links to discounts and send them to telegram

Quick start:

chown -R www-data:www-data database/
chmod 777 -R database

./vendor/bin/sail up

./vendor/bin/sail artisan schedule:work

**FIXES**
- Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use
sudo lsof -i:8080
kill *ID*
./vendor/bin/sail down
./vendor/bin/sail up
