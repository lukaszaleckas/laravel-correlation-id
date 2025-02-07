<?php

namespace LaravelCorrelationId\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware;
use LaravelCorrelationId\JobDispatcher;
use LaravelCorrelationId\Listeners\SetCorrelationId;

class CorrelationIdServiceProvider extends ServiceProvider
{
    public function boot(Kernel $kernel): void
    {
        $this->publishes([
            __DIR__ . '/../config/correlation_id.php' => config_path('correlation_id.php'),
        ], 'laravel-correlation-id');

        $this->pushGlobalMiddleware($kernel);
        $this->prepareEventListeners();
    }

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

    private function pushGlobalMiddleware(Kernel $kernel): void
    {
        if (!$this->shouldIncludeRouteMiddleware()) {
            return;
        }

        $kernel->pushMiddleware(CorrelationIdMiddleware::class);
    }

    private function prepareEventListeners(): void
    {
        Queue::before(SetCorrelationId::class);
    }

    private function shouldIncludeRouteMiddleware(): bool
    {
        return config('correlation_id.include_route_middleware') ?? true;
    }
}
