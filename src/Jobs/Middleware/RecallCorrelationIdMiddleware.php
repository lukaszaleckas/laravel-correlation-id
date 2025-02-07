<?php

namespace LaravelCorrelationId\Jobs\Middleware;

use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;

/**
 * @deprecated This logic is handled by the event listener in the package's service provider.
 */
class RecallCorrelationIdMiddleware
{
    /** @var CorrelationIdService */
    private CorrelationIdService $correlationIdService;

    public function __construct(CorrelationIdService $correlationIdService)
    {
        $this->correlationIdService = $correlationIdService;
    }

    public function handle(mixed $job, mixed $next): mixed
    {
        if ($job instanceof AbstractCorrelatableJob) {
            $this->correlationIdService->setCurrentCorrelationId(
                $job->getCorrelationId()
            );
        }

        return $next($job);
    }
}
