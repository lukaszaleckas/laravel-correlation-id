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
    public function dispatch($command)
    {
        if ($command instanceof AbstractCorrelatableJob) {
            $command->setCorrelationId(
                $this->correlationIdService->getCurrentCorrelationId()
            );
        }

        return parent::dispatch($command);
    }
}
