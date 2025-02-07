<?php

namespace LaravelCorrelationId\Tests\Feature\Jobs\Middleware;

use Exception;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;
use LaravelCorrelationId\Jobs\Middleware\RecallCorrelationIdMiddleware;
use LaravelCorrelationId\Tests\AbstractTest;

class RecallCorrelationIdMiddlewareTest extends AbstractTest
{
    /** @var RecallCorrelationIdMiddleware */
    private $middleware;

    /** @var CorrelationIdService */
    private $correlationIdService;

    /** @var AbstractCorrelatableJob */
    private $job;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware           = app(RecallCorrelationIdMiddleware::class);
        $this->correlationIdService = app(CorrelationIdService::class);
        $this->job                  = new class extends AbstractCorrelatableJob{
        };

        $this->job->setCorrelationId($this->correlationIdService->generateCorrelationId());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSetsCurrentCorrelationId(): void
    {
        $this->middleware->handle(
            $this->job,
            function ($job) {
                return $job;
            }
        );

        self::assertEquals(
            $this->job->getCorrelationId(),
            $this->correlationIdService->getCurrentCorrelationId()
        );
    }
}
