# Laravel Correlation ID

This package offers a way to correlate all the operations performed on request, asynchronous
ones like jobs included.

Correlation id is generated or set from request header, if it has one (header name is
specified in config), and appended to every log context.

## Installation

1. Run `composer require lukaszaleckas/laravel-correlation-id`
2. (optional) Publish `correlation_id.php` config: `php artisan vendor:publish --tag=laravel-correlation-id` if you want to customize the default parameters
3. (optional) Add `LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId` trait to your jobs
if you want correlation id to be automatically saved on job dispatch and recalled before
it is handled / processed.

## Usage

Firstly, you should inject `LaravelCorrelationId\CorrelationIdService` service.

Current correlation id can be accessed by calling service's `getCurrentCorrelationId` method.

This package extends Laravel's default `Dispatcher` class so that the correlation ID could be set before the job is dispatched, and recalled before it is processed.

This package adds `LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware` middleware to the global middleware stack by default.
If you want to add the middleware to specific routes/groups on your own, you can disable this behaviour by setting `CORRELATION_ID_INCLUDE_ROUTE_MIDDLEWARE` environment variable to `false`.

## Note on jobs

If you are mocking Laravel's `Dispatcher::class` in your tests, you should update them
to mock this package's `JobDispatcher` class:

```php
    Mockery::mock(JobDispatcher::class)->shouldReceive('something')->...
```

## Note on Guzzle

If you are using Guzzle and want to pass the correlation ID from one application to another, you will find the `\LaravelCorrelationId\Utils\GuzzleUtils\` class useful.
It exposes 2 methods:
* `getHandlerStack` for use with `GuzzleHttp\Client`'s `handler` option
* `getMiddleware` for use with Laravel's HTTP client facade.

Using these methods will ensure that the client passes the correlation ID header and value to another application.

## Upgrading

### From 1.x to 2.x

These versions should not have any breaking changes.
However, version `2.x` deprecates the `\LaravelCorrelationId\Jobs\Middleware\RecallCorrelationIdMiddleware` and `\LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob` classes.

If you still use them, please consider upgrading to use the new `\LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId` job trait.

Version 2.x also automatically adds the `\LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware` as a global middleware, so you no longer have to manually do that.
