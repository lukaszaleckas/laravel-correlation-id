<?php

namespace LaravelCorrelationId\Listeners;

use Illuminate\Queue\Events\JobProcessing;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;
use LaravelCorrelationId\Utils\Helpers;

class SetCorrelationId
{
    /**
     * @param CorrelationIdService $correlationIdService
     */
    public function __construct(
        protected CorrelationIdService $correlationIdService
    ) {
    }

    /**
     * @param JobProcessing $event
     * @return void
     */
    public function handle(JobProcessing $event): void
    {
        if (!Helpers::hasTrait($event->job->resolveName(), RecallsCorrelationId::class)) {
            return;
        }

        $command = unserialize($event->job->payload()['data']['command']);

        $this->correlationIdService->setCurrentCorrelationId(
            $command->getCorrelationId()
        );
    }
}
