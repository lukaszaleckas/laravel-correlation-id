<?php

namespace LaravelCorrelationId\Jobs\Middleware;

use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;

class RecallCorrelationIdMiddleware
{
    /** @var CorrelationIdService */
    private $correlationIdService;

    /**
     * @param CorrelationIdService $correlationIdService
     */
    public function __construct(CorrelationIdService $correlationIdService)
    {
        $this->correlationIdService = $correlationIdService;
    }

    /**
     * @param mixed $job
     * @param mixed $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        if ($job instanceof AbstractCorrelatableJob) {
            $this->correlationIdService->setCurrentCorrelationId(
                $job->getCorrelationId()
            );
        }

        return $next($job);
    }
}
