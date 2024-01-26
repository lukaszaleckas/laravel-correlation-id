<?php

namespace LaravelCorrelationId\Utils;

use Closure;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\App;
use LaravelCorrelationId\CorrelationIdService;
use Psr\Http\Message\RequestInterface;

class GuzzleUtils
{
    /**
     * @return HandlerStack
     */
    public static function getHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $stack->push(Middleware::mapRequest(self::getMiddleware()), 'correlation_id');

        return $stack;
    }

    /**
     * @return Closure
     */
    public static function getMiddleware(): Closure
    {
        $correlationIdService = App::make(CorrelationIdService::class);

        return fn(RequestInterface $request) => $request->withHeader(
            $correlationIdService->getHttpHeaderName(),
            $correlationIdService->getCurrentCorrelationId()
        );
    }
}
