# SALEPARSER
## _The Telegram Bot With Discounts_

![Build Status](https://github.com/cicirello/user-statistician/actions/workflows/build.yml/badge.svg)

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
