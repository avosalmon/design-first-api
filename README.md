# Design-First API Development in Laravel

## API docs

API docs are available at https://avosalmon.stoplight.io/docs/design-first-api

## System requirements

Make sure Docker is installed on your machine.

## Setup local environment

This repository uses [Laravel Sail](https://laravel.com/docs/9.x/sail) for the local docker environment. You can use the `sail` command by configuring a bash alias below.

```sh
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

Install composer dependencies.

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Start docker compose

```sh
sail up -d
```

Generate app key and places inside the .env file

```sh
sail artisan key:generate
```

Run DB migration

```sh
sail artisan migrate:fresh --seed
```

Now you can access the app via http://localhost:8000.

To stop Docker containers

```sh
sail down
```

## Testing

This repository uses [Pest](https://pestphp.com/) for writing tests. Pest is a testing framework with a simpler syntax like [Jest](https://jestjs.io/) and better reporting. Since it's powered by PHPUnit, it supports all the PHPUnit syntaxes as well.

### Running tests

```sh
sail test
```

### Filtering tests

```sh
sail test --filter ExampleTest
```

### Display code coverage

```sh
sail test --coverage --min=80
```
