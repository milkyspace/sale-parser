# SALEPARSER
## _the telegram bot with discounts_

![Build Status](https://github.com/cicirello/user-statistician/actions/workflows/build.yml/badge.svg)
![License](https://camo.githubusercontent.com/1d6003c64bb7ec42e1a5a5de905f193b59b0127a6136004cd52deb59022df51f/68747470733a2f2f696d672e736869656c64732e696f2f6769746875622f6c6963656e73652f6369636972656c6c6f2f757365722d73746174697374696369616e)

Discount links are sent to your Telegram

## Installation

Install the dependencies and start the server.

```sh
chown -R www-data:www-data database/
chmod 777 -R database
./vendor/bin/sail up
./vendor/bin/sail artisan schedule:work
```

## Fixes

Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use

```sh
sudo lsof -i:8080
kill *ID*
./vendor/bin/sail down
./vendor/bin/sail up
```
