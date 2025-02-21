# Castable – Flexible Attribute-Based Data Casting for PHP
Castable is a lightweight and extensible PHP library that enables seamless attribute-based data transformation and casting. Designed for modern PHP applications, it allows you to define custom casters via attributes in constructor arguments, ensuring precise and automatic data formatting.

Features
✅ Attribute-Based Casting – Define how your properties should be formatted directly in the constructor using PHP attributes.
✅ Extensible Casters – Easily create and register custom casters for different data types.
✅ Seamless Object to Array Conversion – Automatically apply transformations when converting objects to arrays.
✅ Support for DateTime, Boolean, Enum, and Custom Formats – Flexible handling of various data types.
✅ PSR-Friendly & Framework-Agnostic – Works with any PHP project, including Laravel, Symfony, and standalone applications.

## Example Usage
```php
use Castable\Traits\ToArray;
use Castable\Attributes\CastWith;
use Castable\Casters\DateTimeCaster;
use Castable\Casters\BooleanCaster;

class Example
{
use ToArray;

    public DateTimeInterface $createdAt;
    public DateTimeInterface $updatedAt;
    public bool $isActive;

    public function __construct(
        #[CastWith(DateTimeCaster::class, ['d.m.Y H:i'])] 
        DateTimeInterface $createdAt,

        #[CastWith(DateTimeCaster::class, ['Y-m-d H:i:s'])]
        DateTimeInterface $updatedAt,

        #[CastWith(BooleanCaster::class)]
        bool $isActive
    ) {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isActive = $isActive;
    }
}

$example = new Example(
new \DateTime('2024-02-21 12:00:00'),
new \DateTime('2025-02-21 18:30:00'),
true
);

print_r($example->toArray());
```

## Installation
```sh
composer require postfriday/castable
```

## Why Use Castable?
If you're tired of manually formatting your objects when converting them to arrays, Castable provides a clean, declarative, and reusable way to handle data transformation.

👉 Simplify your PHP data handling with Castable! 🚀
