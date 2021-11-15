<?php

namespace LaravelCorrelationId;

use Illuminate\Bus\Dispatcher;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;

class JobDispatcher extends Dispatcher
{
    /** @var CorrelationIdService */
    private $correlationIdService;

    /**
     * @param CorrelationIdService $correlationIdService
     * @param Dispatcher           $dispatcher
     */
    public function __construct(
        CorrelationIdService $correlationIdService,
        Dispatcher $dispatcher
    ) {
        $this->correlationIdService = $correlationIdService;
        parent::__construct($dispatcher->container, $dispatcher->queueResolver);
    }

    /**
     * @param mixed $command
     * @return mixed
     */
    public function dispatchToQueue($command)
    {
        $this->handleCorrelationId($command);

        return parent::dispatchToQueue($command);
    }

    /**
     * @param mixed $command
     * @param mixed $handler
     * @return mixed
     */
    public function dispatchNow($command, $handler = null)
    {
        $this->handleCorrelationId($command);

        return parent::dispatchNow($command, $handler);
    }

    /**
     * @param mixed $command
     * @return void
     */
    private function handleCorrelationId($command): void
    {
        if ($command instanceof AbstractCorrelatableJob) {
            $command->setCorrelationId(
                $this->correlationIdService->getCurrentCorrelationId()
            );
        }
    }
}
