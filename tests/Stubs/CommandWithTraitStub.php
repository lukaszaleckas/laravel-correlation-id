<?php

namespace LaravelCorrelationId\Tests\Stubs;

use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;

class CommandWithTraitStub
{
    use RecallsCorrelationId;
}
