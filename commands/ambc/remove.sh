#!/usr/bin/env bash

docker container rm ambc_api
docker container rm ambc_phpmyadmin
docker container rm ambc_master_db
docker container rm ambc_cache
docker container rm ambc_slave_db_1
docker container rm ambc_slave_db_2