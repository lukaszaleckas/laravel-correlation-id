<?php

namespace LaravelCorrelationId\Tests\Feature\Http\Middleware;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Http\Middleware\CorrelationIdMiddleware;
use LaravelCorrelationId\Tests\AbstractTest;

class CorrelationIdMiddlewareTest extends AbstractTest
{
    use WithFaker;

    /** @var CorrelationIdMiddleware */
    private $middleware;

    /** @var string */
    private $headerName;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->headerName = $this->faker->word;

        Config::set('correlation_id.header_name', $this->headerName);

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
            $request->header($this->headerName, ''),
            app(CorrelationIdService::class)->getCurrentCorrelationId()
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
            $request->header($this->headerName)
        );
    }
}
