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

    /** @var string */
    private $httpHeaderName;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->currentCorrelationId = null;
        $this->logContextKey        = config('correlation_id.log_context_key');
        $this->httpHeaderName       = config('correlation_id.header_name');
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
     * @return string
     */
    public function getLogContextKey(): string
    {
        return $this->logContextKey;
    }

    /**
     * @return string
     */
    public function getHttpHeaderName(): string
    {
        return $this->httpHeaderName;
    }

    /**
     * @return void
     */
    private function updateLogContext(): void
    {
        Log::withContext([
            $this->getLogContextKey() => $this->getCurrentCorrelationId()
        ]);
    }
}
