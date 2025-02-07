<?php

namespace LaravelCorrelationId;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CorrelationIdService
{
    private const DEFAULT_LOG_CONTEXT_KEY  = 'correlation_id';
    private const DEFAULT_HTTP_HEADER_NAME = 'X-CORRELATION-ID';

    /** @var string|null */
    private ?string $currentCorrelationId;

    /** @var string */
    private string $logContextKey;

    /** @var string */
    private string $httpHeaderName;

    public function __construct()
    {
        $this->currentCorrelationId = null;
        $this->logContextKey        = config('correlation_id.log_context_key', self::DEFAULT_LOG_CONTEXT_KEY);
        $this->httpHeaderName       = config('correlation_id.header_name', self::DEFAULT_HTTP_HEADER_NAME);
    }

    public function generateCorrelationId(): string
    {
        return Str::orderedUuid()->toString();
    }

    public function getCurrentCorrelationId(): ?string
    {
        if ($this->currentCorrelationId === null) {
            $this->setCurrentCorrelationId(
                $this->generateCorrelationId()
            );
        }

        return $this->currentCorrelationId;
    }

    public function setCurrentCorrelationId(?string $currentCorrelationId): void
    {
        $this->currentCorrelationId = $currentCorrelationId;

        $this->updateLogContext();
    }

    public function getLogContextKey(): string
    {
        return $this->logContextKey;
    }

    public function getHttpHeaderName(): string
    {
        return $this->httpHeaderName;
    }

    private function updateLogContext(): void
    {
        Log::withContext([
            $this->getLogContextKey() => $this->getCurrentCorrelationId()
        ]);
    }
}
