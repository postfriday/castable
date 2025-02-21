<?php

namespace Postfriday\Castable\Tests\Traits;

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Postfriday\Castable\Casters\BooleanCaster;
use Postfriday\Castable\Casters\DateTimeCaster;
use Postfriday\Castable\Tests\BaseTest;
use Postfriday\Castable\Traits\ToArray;
use Postfriday\Castable\Attributes\CastWith;


class Example
{
    use ToArray;


    public function __construct(
        #[CastWith(DateTimeCaster::class, ['d.m.Y H:i'])]
        public DateTimeInterface $createdAt,

        #[CastWith(DateTimeCaster::class, ['Y-m-d H:i:s'])]
        public DateTimeInterface $updatedAt,

        #[CastWith(BooleanCaster::class)]
        public bool $isActive,

        public ?string $name
    )
    {
        //
    }
}


#[CoversClass(ToArray::class)]
class ToArrayTest extends BaseTest
{
    private Example $example;

    protected function setUp(): void
    {
        $this->example = new Example(
            new DateTime('2024-02-21 12:00:00'),
            new DateTime('2025-02-21 18:30:00'),
            true,
            'John Doe'
        );
    }


    public function test_it_converts_object_to_array()
    {
        $result = $this->example->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertArrayHasKey('updatedAt', $result);
        $this->assertArrayHasKey('isActive', $result);
        $this->assertArrayHasKey('name', $result);
    }


    public function test_it_applies_datetime_casting_correctly(): void
    {
        $result = $this->example->toArray();

        $this->assertSame('21.02.2024 12:00', $result['createdAt']);
        $this->assertSame('2025-02-21 18:30:00', $result['updatedAt']);
    }


    public function test_it_converts_boolean_correctly(): void
    {
        $result = $this->example->toArray();

        $this->assertSame('true', $result['isActive']);
    }


    public function test_it_removes_null_values_if_enabled()
    {
        $this->example->name = null;
        $result = $this->example->toArray(
            removeNullValues: true
        );

        $this->assertArrayNotHasKey('name', $result);
    }


    public function test_it_keeps_null_values_if_disabled()
    {
        $this->example->name = null;
        $result = $this->example->toArray(
            removeNullValues: false
        );

        $this->assertArrayHasKey('name', $result);
        $this->assertNull($result['name']);
    }


    public function test_it_preserves_array_structure(): void
    {
        $nestedObject = new Example(
            new DateTime('2023-01-01 08:00:00'),
            new DateTime('2024-06-15 14:00:00'),
            false,
            'Nested Object'
        );

        $this->example->nested = $nestedObject;

        $result = $this->example->toArray();

        $this->assertArrayHasKey('nested', $result);
        $this->assertIsArray($result['nested']);
        $this->assertSame('01.01.2023 08:00', $result['nested']['createdAt']);
        $this->assertSame('2024-06-15 14:00:00', $result['nested']['updatedAt']);
        $this->assertSame('false', $result['nested']['isActive']);
        $this->assertSame('Nested Object', $result['nested']['name']);
    }
}
