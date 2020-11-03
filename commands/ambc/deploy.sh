#!/usr/bin/env bash

if [[ $# -eq 0 ]] ; then
    echo 'Please provide container name.'
    exit 1
fi

$dockerExecuteContainers="docker exec -it $1";

#composer update --no-interaction --no-progress  --prefer-dist --optimize-autoloader --ignore-platform-reqs
docker exec -it $1 php artisan optimize
docker exec -it $1 php artisan migrate --seed
