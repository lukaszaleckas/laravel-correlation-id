<?php

return [
    /*
     * Request's header name. If request includes this header, specified correlation ID will be
     * saved for the current request / job lifecycle, if not - random uuid will be generated.
     */
    'header_name'     => env('CORRELATION_ID_HEADER_NAME', 'X-CORRELATION-ID'),

    /*
     * Log's context array key name. Correlation ID with this name will be appended to all logs'
     * context.
     */
    'log_context_key' => env('CORRELATION_ID_LOG_CONTEXT_KEY', 'correlation_id')
];
