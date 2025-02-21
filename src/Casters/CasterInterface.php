<?php

namespace Postfriday\Castable\Casters;

interface CasterInterface
{
    public function cast(mixed $value): mixed;
}
