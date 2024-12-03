# Travel Expense Tracker

Telegram bot for tracking expenses in joint trips.

## Installation

Copy the environment variables and change for your needs:

```shell
cp .env.dist .env
```

Start docker containers:

```shell
make up
```

Install composer dependencies:

```shell
make exec ARGS="composer install"
```

Setup telegram webhook:

```shell
php bin/console telegram:webhook:set
```
