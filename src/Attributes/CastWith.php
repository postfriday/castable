<?php

namespace Postfriday\Castable\Attributes;

use Attribute;

#[Attribute]
class CastWith
{
    /**
     * @param string $casterClass
     * @param array<int|string|bool|object|float> $arguments
     */
    public function __construct(
        public string $casterClass,
        public array $arguments = []
    )
    {
        //
    }
}
