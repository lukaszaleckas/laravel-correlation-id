<?php

namespace LaravelCorrelationId;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;
use LaravelCorrelationId\Utils\Helpers;

class JobDispatcher extends Dispatcher
{
    public function __construct(Dispatcher $dispatcher)
    {
        parent::__construct($dispatcher->container, $dispatcher->queueResolver);
    }

    /**
     * @param mixed $command
     * @return mixed
     * @throws BindingResolutionException
     */
    public function dispatchToQueue(mixed $command): mixed
    {
        $this->handleCorrelationId($command);

        return parent::dispatchToQueue($command);
    }

    /**
     * @param mixed $command
     * @param mixed $handler
     * @return mixed
     * @throws BindingResolutionException
     */
    public function dispatchNow(mixed $command, mixed $handler = null): mixed
    {
        $this->handleCorrelationId($command);

        return parent::dispatchNow($command, $handler);
    }

    /**
     * @param mixed $command
     * @return void
     * @throws BindingResolutionException
     */
    private function handleCorrelationId(mixed $command): void
    {
        if (Helpers::hasTrait($command, RecallsCorrelationId::class)) {
            $command->setCorrelationId(
                $this->getCorrelationIdService()->getCurrentCorrelationId()
            );
        }
    }

    /**
     * @return CorrelationIdService
     * @throws BindingResolutionException
     */
    private function getCorrelationIdService(): CorrelationIdService
    {
        return $this->container->make(CorrelationIdService::class);
    }
}
