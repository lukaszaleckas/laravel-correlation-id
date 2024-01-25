<?php

namespace LaravelCorrelationId\Jobs\Traits;

trait RecallsCorrelationId
{
    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

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
