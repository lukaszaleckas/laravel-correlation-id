<?php

namespace LaravelCorrelationId\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use LaravelCorrelationId\Providers\CorrelationIdServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class AbstractTest extends TestCase
{
    use WithFaker;

    /** @var string */
    protected $headerName;

    /** @var string */
    protected $logContextKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headerName    = $this->faker->word;
        $this->logContextKey = $this->faker->word;

        Config::set('correlation_id.header_name', $this->headerName);
        Config::set('correlation_id.log_context_key', $this->logContextKey);
    }

    protected function getPackageProviders($app): array
    {
        return [
            CorrelationIdServiceProvider::class
        ];
    }
}
