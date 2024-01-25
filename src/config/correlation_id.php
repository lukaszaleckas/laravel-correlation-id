<?php

return [
    /*
     * Http header name.
     *
     * In case of request: if it includes this header, specified correlation ID will be
     * saved for the current request / job lifecycle, if not - random uuid will be generated.
     *
     * In case of response: it will include this header with correlation ID.
     */
    'header_name'              => env('CORRELATION_ID_HEADER_NAME', 'X-CORRELATION-ID'),

    /*
     * Log's context array key name. Correlation ID with this name will be appended to all logs'
     * context.
     */
    'log_context_key'          => env('CORRELATION_ID_LOG_CONTEXT_KEY', 'correlation_id'),

    /*
     * Whether to include route middlewares globally by default.
     *
     * If set to true, the CorrelationIdMiddleware will be included in the global middlewares
     * by the CorrelationIdServiceProvider.
     *
     * You should set this to false if you only want the correlation ID header to be handled
     * only on some requests
     */
    'include_route_middleware' => env('CORRELATION_ID_INCLUDE_ROUTE_MIDDLEWARE', true),
];
