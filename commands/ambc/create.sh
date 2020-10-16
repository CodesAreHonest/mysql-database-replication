#!/usr/bin/env bash

cp .env.docker.example .env
cd ./images/php || exit
cp .env.ambc.example ./ambc/.env

cd ./ambc || exit
composer update
