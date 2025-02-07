<?php

namespace LaravelCorrelationId\Tests\Feature;

use Illuminate\Support\Facades\App;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Tests\AbstractTest;

class CorrelationIdServiceTest extends AbstractTest
{
    public function testGetCurrentCorrelationIdSetsRandomCorrelationIdWhenItIsNull(): void
    {
        $correlationIdService = App::make(CorrelationIdService::class);

        $correlationIdService->setCurrentCorrelationId(null);

        $this->assertNotNull($correlationIdService->getCurrentCorrelationId());
    }
}
