# Laravel Correlation ID

This package offers a way to correlate all the operations performed on request, asynchronous
ones like jobs included.

Correlation id is generated or set from request header, if it has one (header name is
specified in config), and appended to every log context.

## Installation

1. Run `composer require lukaszaleckas/laravel-correlation-id`
2. Publish `correlation_id.php` config: `php artisan vendor:publish --tag=laravel-correlation-id`
3. Add `LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware` middleware to your 
Http kernel.
4. (optional) Extend your jobs from `LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob`
if you want correlation id to be automatically saved on job dispatch and recalled before
it is handled / processed.

## Usage

Firstly, you should inject `LaravelCorrelationId\CorrelationIdService` service.

Current correlation id can be accessed by calling service's `getCurrentCorrelationId` method.

## Note on jobs

Correlation id is automatically saved (protected variable `$correlationId` is set) on job dispatch
and recalled (set in `CorrelationIdService` scoped singleton) using job middleware. If your job is already
using middleware(s), you should remember to merge them with `AbstractCorrelatableJob` ones:

```php
    public function middleware(): array
    {
        return array_merge(
            parent::middleware(),
            [
                //your middleware
            ]
        );
    }
```

If you are mocking Laravel's `Dispatcher::class` in your tests, you should update them
to mock this package's `JobDispatcher` class:

```php
    Mockery::mock(JobDispatcher::class)->shouldReceive('something')->...
```
