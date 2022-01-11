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

    /**
     * @return void
     */
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
        $request = new Request();
        $this->middleware->handle($request, function ($request) {
            return $request;
        });

        self::assertEquals(
            app(CorrelationIdService::class)->getCurrentCorrelationId(),
            $request->header($this->headerName, '')
        );
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
        $this->middleware->handle($request, function ($request) {
            return $request;
        });

        self::assertEquals(
            $correlationId,
            app(CorrelationIdService::class)->getCurrentCorrelationId()
        );
    }
}
