<?php

namespace Postfriday\Castable\Traits;

use UnitEnum;
use stdClass;
use ReflectionClass;
use Postfriday\Castable\Attributes\CastWith;
use Postfriday\Castable\Casters\CasterInterface;

trait ToArray
{
    protected function getCasters(): array
    {
        $casters = [];

        $reflection = new ReflectionClass($this);
        $constructor = $reflection->getConstructor();

        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $attributes = $param->getAttributes(CastWith::class);

                if (!empty($attributes)) {
                    /** @var CastWith $castWith */
                    $castWith = $attributes[0]->newInstance();
                    $caster = new $castWith->casterClass(...$castWith->arguments);

                    if ($caster instanceof CasterInterface) {
                        $casters[$param->getName()] = $caster;
                    }
                }
            }
        }

        return $casters;
    }


    protected function castValues(
        object $object,
        array $casters,
        string $propertyName = null
    ) {
        if (isset($casters[$propertyName])) {
            return $casters[$propertyName]->cast($object);
        }

        if ($object instanceof UnitEnum) {
            return $object->value;
        }

        if ($object instanceof stdClass) {
            return (array) $object;
        }

        if (method_exists($object, 'toArray')) {
            return $object->toArray();
        }

        return get_object_vars($object);
    }


    public function transformArray(
        array $array,
        bool $transformBoolean,
        array $casters,
        ?string $parentProperty = null
    ): array {
        $result = [];

        foreach ($array as $key => $element) {
            $propertyName = $parentProperty ? "{$parentProperty}.{$key}" : $key;

            $result[$key] = match (gettype($element)) {
                'boolean' => isset($casters[$propertyName])
                    ? $casters[$propertyName]->cast($element)
                    : ($transformBoolean ? ($element ? 'true' : 'false') : $element),
                'object' => $this->castValues($element, $casters, $propertyName),
                'array' => $this->transformArray($element, $transformBoolean, $casters, $propertyName),
                default => $element
            };
        }

        return $result;
    }


    public function toArray(
        bool $transformBoolean = false,
        bool $removeNullValues = true
    ): array {
        $casters = $this->getCasters();

        $array = $this->transformArray(get_object_vars($this), $transformBoolean, $casters);

        return $removeNullValues ? array_filter($array, fn ($value) => !is_null($value)) : $array;
    }
}
