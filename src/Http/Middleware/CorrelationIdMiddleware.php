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
        $headerName = $this->correlationIdService->getHttpHeaderName();

        if (!$request->hasHeader($headerName)) {
            $request->headers->set($headerName, $this->correlationIdService->generateCorrelationId());
        }
        
        $currentCorrelationId = $request->headers->get($headerName);
        $this->correlationIdService->setCurrentCorrelationId($currentCorrelationId);

        $response = $next($request);
        $response->headers->set($headerName, $currentCorrelationId);

        return $response;
    }
}
