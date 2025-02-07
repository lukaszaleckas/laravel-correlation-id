<?php

namespace LaravelCorrelationId\Tests;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Queue;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\JobDispatcher;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;

class JobDispatcherTest extends AbstractTest
{
    /** @var JobDispatcher */
    private $jobDispatcher;

    /** @var CorrelationIdService */
    private $correlationIdService;

    /** @var AbstractCorrelatableJob */
    private $job;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var JobDispatcher $jobDispatcher */
        $jobDispatcher = app(Dispatcher::class);

        $this->jobDispatcher        = $jobDispatcher;
        $this->correlationIdService = app(CorrelationIdService::class);
        $this->job                  = new class extends AbstractCorrelatableJob{
            /** @return void */
            public function __invoke()
            {
            }
        };

        $this->correlationIdService->setCurrentCorrelationId(
            $this->correlationIdService->generateCorrelationId()
        );
    }

    public function testSetsJobCorrelationIdOnDispatch(): void
    {
        $this->jobDispatcher->dispatch($this->job);

        $this->assertCorrelationIdWasSet();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testSetsJobCorrelationIdOnDispatchToQueue(): void
    {
        Queue::fake();

        $this->jobDispatcher->dispatchToQueue($this->job);

        $this->assertCorrelationIdWasSet();
    }

    public function testSetsJobCorrelationIdOnSynchronousDispatch(): void
    {
        $this->jobDispatcher->dispatchSync($this->job);

        $this->assertCorrelationIdWasSet();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testSetsJobCorrelationIdWhenDispatchingNow(): void
    {
        $this->jobDispatcher->dispatchNow($this->job);

        $this->assertCorrelationIdWasSet();
    }

    public function testRefreshesServiceAfterFlushingScopedInstances(): void
    {
        $this->app->forgetScopedInstances();

        /** @var CorrelationIdService $correlationIdService */
        $correlationIdService = app(CorrelationIdService::class);

        $correlationIdService->setCurrentCorrelationId(
            $correlationIdService->generateCorrelationId()
        );

        $this->jobDispatcher->dispatch($this->job);

        self::assertEquals(
            $correlationIdService->getCurrentCorrelationId(),
            $this->job->getCorrelationId()
        );
    }

    private function assertCorrelationIdWasSet(): void
    {
        self::assertEquals(
            $this->correlationIdService->getCurrentCorrelationId(),
            $this->job->getCorrelationId()
        );
    }
}
