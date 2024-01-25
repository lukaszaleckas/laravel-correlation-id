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
    /**
     * @param Kernel $kernel
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
        $this->publishes([
            __DIR__ . '/../config/correlation_id.php' => config_path('correlation_id.php'),
        ], 'laravel-correlation-id');

        $this->pushGlobalMiddleware($kernel);
        $this->prepareEventListeners();
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

    /**
     * @param Kernel $kernel
     * @return void
     */
    private function pushGlobalMiddleware(Kernel $kernel): void
    {
        if (!$this->shouldIncludeRouteMiddleware()) {
            return;
        }

        $kernel->pushMiddleware(CorrelationIdMiddleware::class);
    }

    /**
     * @return void
     */
    private function prepareEventListeners(): void
    {
        Queue::before(SetCorrelationId::class);
    }

    /**
     * @return bool
     */
    private function shouldIncludeRouteMiddleware(): bool
    {
        return config('correlation_id.include_route_middleware') ?? true;
    }
}
