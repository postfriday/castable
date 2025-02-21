<?php

namespace Postfriday\Castable\Casters;

use DateTimeInterface;

class DateTimeCaster implements CasterInterface
{
    public function __construct(
        protected string $format = 'Y-m-d'
    )
    {
        //
    }

    public function cast(mixed $value): mixed
    {
        return $value instanceof DateTimeInterface ? $value->format($this->format) : $value;
    }
}
