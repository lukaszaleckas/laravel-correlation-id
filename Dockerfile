FROM composer:2 as builder

COPY composer.json /app/

RUN composer install  \
  --no-ansi \
  --no-autoloader \
  --no-interaction \
  --no-scripts

COPY . /app/

RUN composer dump-autoload --optimize --classmap-authoritative

FROM php:8.1-cli as base

# Install PECL and PEAR extensions
RUN pecl install xdebug

# Enable php extensions
RUN docker-php-ext-enable xdebug

# Add php extensions configuration
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Cleanup
RUN rm -rf /var/lib/apt/lists/*
RUN rm -rf /tmp/pear/

# Setup working directory
WORKDIR /app

COPY --from=builder /app /app
