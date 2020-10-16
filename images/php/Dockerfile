FROM php:7.4-alpine

RUN apt-get update && apt-get install -y build-essential \
    nano --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Clear cache after update and upgrades
RUN apt-get clean && rm -rf /var/lib/apt/lists/* && apt-get autoremove

# lumen packages
RUN docker-php-ext-install intl iconv bcmath opcache zip json gd curl xml tokenizer mysqli pdo_mysql
