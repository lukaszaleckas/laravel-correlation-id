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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(CorrelationIdServiceProvider::class);

        $this->headerName    = $this->faker->word;
        $this->logContextKey = $this->faker->word;

        Config::set('correlation_id.header_name', $this->headerName);
        Config::set('correlation_id.log_context_key', $this->logContextKey);
    }
}
