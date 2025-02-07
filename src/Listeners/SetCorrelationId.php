<?php

namespace LaravelCorrelationId\Listeners;

use Illuminate\Queue\Events\JobProcessing;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;
use LaravelCorrelationId\Utils\Helpers;

class SetCorrelationId
{
    public function __construct(
        protected CorrelationIdService $correlationIdService
    ) {
    }

    public function handle(JobProcessing $event): void
    {
        if (!$this->shouldRecallCorrelationId($event)) {
            return;
        }

        $this->setCurrentCorrelationId(
            $this->getJobCommand($event)
        );
    }

    private function shouldRecallCorrelationId(JobProcessing $event): bool
    {
        return Helpers::hasTrait($event->job->resolveName(), RecallsCorrelationId::class);
    }

    private function getJobCommand(JobProcessing $event): mixed
    {
        $command = $event->job->payload()['data']['command'];

        if ($this->isCommandEncrypted($command)) {
            $command = decrypt($command);
        }

        return unserialize($command);
    }

    private function setCurrentCorrelationId(mixed $command): void
    {
        $this->correlationIdService->setCurrentCorrelationId(
            $command->getCorrelationId()
        );
    }

    private function isCommandEncrypted(mixed $command): bool
    {
        return !str_starts_with($command, 'O:');
    }
}
