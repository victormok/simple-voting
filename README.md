# Simple voting

## Table of contents

- [General info](#general-info)
- [Technologies](#technologies)
- [Local environment Setup](#local-environment-setup)
- [Migration](#Migration)

## General info

Simple voting is voting system api for users to express their opinions for news topics.

## Technologies

Project is created with:

- PHP version: 8.1.13
- Laravel version: 9.41.0
- Mysql version: 8.0

## Local environment setup

To run this project, install it locally using composer:

```
$ cd simple-voting-api
$ composer install
$ php artisan serve
```

Fill your .env file for necessary information

```
APP_KEY=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

## Migration

After connected the database, you can run follow command to create table and start the server.

```
$ php artisan migrate

$ php artisan serve
```
