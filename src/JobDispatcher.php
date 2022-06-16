<?php

namespace LaravelCorrelationId;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;

class JobDispatcher extends Dispatcher
{
    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        parent::__construct($dispatcher->container, $dispatcher->queueResolver);
    }

    /**
     * @param mixed $command
     * @return mixed
     * @throws BindingResolutionException
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
     * @throws BindingResolutionException
     */
    public function dispatchNow($command, $handler = null)
    {
        $this->handleCorrelationId($command);

        return parent::dispatchNow($command, $handler);
    }

    /**
     * @param mixed $command
     * @return void
     * @throws BindingResolutionException
     */
    private function handleCorrelationId($command): void
    {
        if ($command instanceof AbstractCorrelatableJob) {
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
