<?php

namespace LaravelCorrelationId\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelCorrelationId\CorrelationIdService;

class CorrelationIdMiddleware
{
    /** @var CorrelationIdService */
    private $correlationIdService;

    /** @var string */
    private $headerName;

    /**
     * @param CorrelationIdService $correlationIdService
     */
    public function __construct(CorrelationIdService $correlationIdService)
    {
        $this->correlationIdService = $correlationIdService;
        $this->headerName           = config('correlation_id.header_name');
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader($this->headerName)) {
            $correlationId = $this->correlationIdService->generateCorrelationId();
            $this->correlationIdService->setCurrentCorrelationId($correlationId);

            $request->headers->set($this->headerName, $correlationId);
        }

        return $next($request);
    }
}
