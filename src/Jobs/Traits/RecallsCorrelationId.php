<?php

namespace LaravelCorrelationId\Jobs\Traits;

trait RecallsCorrelationId
{
    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

    public function setCorrelationId(?string $correlationId): void
    {
        $this->correlationId = $correlationId;
    }

    public function getCorrelationId(): ?string
    {
        return $this->correlationId;
    }
}
