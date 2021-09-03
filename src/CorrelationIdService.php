<?php

namespace LaravelCorrelationId;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CorrelationIdService
{
    /** @var string|null */
    private $currentCorrelationId;

    /** @var string */
    private $logContextKey;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->currentCorrelationId = null;
        $this->logContextKey        = config('correlation_id.log_context_key');
    }

    /**
     * @return string
     */
    public function generateCorrelationId(): string
    {
        return Str::orderedUuid()->toString();
    }

    /**
     * @return string|null
     */
    public function getCurrentCorrelationId(): ?string
    {
        return $this->currentCorrelationId;
    }

    /**
     * @param string|null $currentCorrelationId
     * @return void
     */
    public function setCurrentCorrelationId(?string $currentCorrelationId): void
    {
        $this->currentCorrelationId = $currentCorrelationId;

        $this->updateLogContext();
    }

    /**
     * @return void
     */
    private function updateLogContext(): void
    {
        Log::withContext([
            $this->logContextKey => $this->getCurrentCorrelationId()
        ]);
    }
}
