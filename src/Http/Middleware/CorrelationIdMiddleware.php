<?php

namespace LaravelCorrelationId\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelCorrelationId\CorrelationIdService;

class CorrelationIdMiddleware
{
    /** @var CorrelationIdService */
    private $correlationIdService;

    /**
     * @param CorrelationIdService $correlationIdService
     */
    public function __construct(CorrelationIdService $correlationIdService)
    {
        $this->correlationIdService = $correlationIdService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headerName = $this->correlationIdService->getRequestHeaderName();

        if (!$request->hasHeader($headerName)) {
            $correlationId = $this->correlationIdService->generateCorrelationId();
            $this->correlationIdService->setCurrentCorrelationId($correlationId);

            $request->headers->set($headerName, $correlationId);
        }

        return $next($request);
    }
}
