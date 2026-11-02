#!/usr/bin/env bash

# Laravel Sail Wrapper Script for SPORT.CPE (Database Lab 261343 Compatible)
if [ "$1" = "up" ]; then
    docker compose up "${@:2}"
elif [ "$1" = "down" ]; then
    docker compose down "${@:2}"
elif [ "$1" = "restart" ]; then
    docker compose restart "${@:2}"
elif [ "$1" = "artisan" ]; then
    docker compose exec laravel.test php artisan "${@:2}"
elif [ "$1" = "composer" ]; then
    docker compose exec laravel.test composer "${@:2}"
elif [ "$1" = "tinker" ]; then
    docker compose exec laravel.test php artisan tinker
elif [ -z "$1" ]; then
    docker compose ps
else
    docker compose exec laravel.test "$@"
fi

