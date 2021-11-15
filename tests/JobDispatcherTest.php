<?php

namespace LaravelCorrelationId\Tests;

use Illuminate\Bus\Dispatcher;
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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testSetsJobCorrelationIdOnDispatch(): void
    {
        $this->jobDispatcher->dispatch($this->job);

        $this->assertCorrelationIdWasSet();
    }

    /**
     * @return void
     */
    public function testSetsJobCorrelationIdOnSynchronousDispatch(): void
    {
        $this->jobDispatcher->dispatchSync($this->job);

        $this->assertCorrelationIdWasSet();
    }

    /**
     * @return void
     */
    public function testSetsJobCorrelationIdWhenDispatchingNow(): void
    {
        $this->jobDispatcher->dispatchNow($this->job);

        $this->assertCorrelationIdWasSet();
    }

    /**
     * @return void
     */
    private function assertCorrelationIdWasSet(): void
    {
        self::assertEquals(
            $this->correlationIdService->getCurrentCorrelationId(),
            $this->job->getCorrelationId()
        );
    }
}
