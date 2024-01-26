<?php

namespace LaravelCorrelationId\Tests\Feature\Jobs\Contracts;

use LaravelCorrelationId\Jobs\Contracts\AbstractCorrelatableJob;
use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;
use LaravelCorrelationId\Tests\AbstractTest;

class AbstractCorrelatableJobTest extends AbstractTest
{
    /**
     * @return void
     */
    public function testIncludesTrait(): void
    {
        $job = new class extends AbstractCorrelatableJob{
        };

        self::assertContains(
            RecallsCorrelationId::class,
            class_uses_recursive($job)
        );
    }
}
