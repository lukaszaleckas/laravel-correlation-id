<?php

namespace LaravelCorrelationId\Tests\Feature\Jobs\Contracts;

use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;
use LaravelCorrelationId\Jobs\Middleware\RecallCorrelationIdMiddleware;
use LaravelCorrelationId\Tests\AbstractTest;

class AbstractCorrelatableJobTest extends AbstractTest
{
    /**
     * @return void
     */
    public function testIncludesMiddleware(): void
    {
        $job = new class extends AbstractCorrelatableJob{
        };

        self::assertEquals(
            [RecallCorrelationIdMiddleware::class],
            $job->middleware()
        );
    }
}
