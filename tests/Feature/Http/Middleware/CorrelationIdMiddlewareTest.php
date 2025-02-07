<?php

namespace LaravelCorrelationId\Tests\Feature\Http\Middleware;

use Exception;
use Illuminate\Http\Request;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware;
use LaravelCorrelationId\Tests\AbstractTest;

class CorrelationIdMiddlewareTest extends AbstractTest
{
    /** @var CorrelationIdMiddleware */
    private $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = app(CorrelationIdMiddleware::class);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testAddsCorrelationIdToRequestIfNotPresent(): void
    {
        $request               = new Request();
        $response              = $this->middleware->handle($request, function ($request) {
            return $request;
        });
        $expectedCorrelationId = app(CorrelationIdService::class)->getCurrentCorrelationId();

        self::assertEquals($expectedCorrelationId, $request->header($this->headerName));
        self::assertEquals($expectedCorrelationId, $response->header($this->headerName));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testDoesNotGenerateCorrelationIdIfAlreadyPresent(): void
    {
        $correlationId = $this->faker->uuid;
        $request       = new Request();
        $request->headers->set(
            $this->headerName,
            $correlationId
        );
        $response              = $this->middleware->handle($request, function ($request) {
            return $request;
        });
        $expectedCorrelationId = app(CorrelationIdService::class)->getCurrentCorrelationId();

        self::assertEquals($correlationId, $expectedCorrelationId);
        self::assertEquals($expectedCorrelationId, $response->header($this->headerName));
    }
}
