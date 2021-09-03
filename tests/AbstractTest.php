<?php

namespace LaravelCorrelationId\Tests;

use LaravelCorrelationId\Providers\CorrelationIdServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class AbstractTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(CorrelationIdServiceProvider::class);
    }
}
