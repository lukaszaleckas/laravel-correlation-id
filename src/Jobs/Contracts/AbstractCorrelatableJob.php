<?php

namespace LaravelCorrelationId\Jobs\Contracts;

use LaravelCorrelationId\Jobs\Traits\RecallsCorrelationId;

/**
 * @deprecated Add the RecallsCorrelationId trait to your job instead.
 */
abstract class AbstractCorrelatableJob
{
    use RecallsCorrelationId;
}
