<?php

namespace LaravelCorrelationId\Tests\Feature\Utils;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Tests\AbstractTest;
use LaravelCorrelationId\Utils\GuzzleUtils;

class GuzzleUtilsTest extends AbstractTest
{
    use WithFaker;

    public function testReturnsHandlerStackInstance(): void
    {
        $this->assertInstanceOf(
            HandlerStack::class,
            GuzzleUtils::getHandlerStack()
        );
    }

    public function testGetMiddlewareReturnsCallable(): void
    {
        $this->assertIsCallable(GuzzleUtils::getMiddleware());
    }

    public function testMiddlewareAddsCorrelationIdHeader(): void
    {
        $correlationId        = $this->faker->uuid;
        $middleware           = GuzzleUtils::getMiddleware();
        $correlationIdService = App::make(CorrelationIdService::class);
        $request              = new Request(
            'GET',
            $this->faker->url,
        );

        $correlationIdService->setCurrentCorrelationId($correlationId);

        $request = $middleware($request);

        $this->assertTrue(
            $request->hasHeader($correlationIdService->getHttpHeaderName())
        );

        $this->assertSame(
            $correlationId,
            $request->getHeader($correlationIdService->getHttpHeaderName())[0]
        );
    }
}
