<?php

namespace LaravelCorrelationId\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\JobDispatcher;

class CorrelationIdServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/correlation_id.php' => config_path('correlation_id.php'),
        ], 'laravel-correlation-id');
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->scoped(CorrelationIdService::class);
        $this->app->extend(
            Dispatcher::class,
            function ($service, $app) {
                return new JobDispatcher($service);
            }
        );
    }
}
