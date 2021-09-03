<?php

namespace LaravelCorrelationId\Jobs\Contracts;

use LaravelCorrelationId\Jobs\Middleware\RecallCorrelationIdMiddleware;

abstract class AbstractCorrelatableJob
{
    /** @var string|null */
    protected $correlationId = null;

    /**
     * @return string[]
     */
    public function middleware(): array
    {
        return [
            RecallCorrelationIdMiddleware::class
        ];
    }

    /**
     * @param string|null $correlationId
     * @return void
     */
    public function setCorrelationId(?string $correlationId): void
    {
        $this->correlationId = $correlationId;
    }

    /**
     * @return string|null
     */
    public function getCorrelationId(): ?string
    {
        return $this->correlationId;
    }
}
