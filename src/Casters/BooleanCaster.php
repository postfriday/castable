<?php

namespace Postfriday\Castable\Casters;

class BooleanCaster implements CasterInterface
{
    public function cast(mixed $value): mixed
    {
        return is_bool($value) ? ($value ? 'true' : 'false') : $value;
    }
}
