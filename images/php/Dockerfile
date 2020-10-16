FROM php:7.4-alpine

RUN apk add --no-cache --update zlib-dev libpng-dev

# lumen packages
RUN docker-php-ext-install bcmath opcache gd tokenizer mysqli pdo_mysql
