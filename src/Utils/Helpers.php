<?php

namespace LaravelCorrelationId\Utils;

class Helpers
{
    /**
     * @param object|class-string $class
     * @param class-string        $trait
     * @return bool
     */
    public static function hasTrait(object|string $class, string $trait): bool
    {
        return in_array($trait, class_uses_recursive($class));
    }
}
