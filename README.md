# Laravel Correlation ID

This package sets correlation id, which is accessible by injecting `CorrelationIdService`
scoped singleton and calling its `getCurrentCorrelationId` method. Correlation id
is generated or set from request header if it has one (header name is specified in config)
and appended to log context.

It is also automatically saved on job dispatch if job extends from `AbstractCorrelatableJob`.

## Installation

1. Run `composer require lukaszaleckas/laravel-correlation-id`
2. Publish config `php artisan vendor:publish --tag=laravel-correlation-id`
