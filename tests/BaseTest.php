<?php

namespace Postfriday\Castable\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BaseTest::class)]
class BaseTest extends TestCase
{
    public function testIfTrue()
    {
        $this->assertTrue(true);
    }
}
