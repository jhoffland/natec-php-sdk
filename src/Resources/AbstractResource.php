<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use BackedEnum;
use DateTimeImmutable;
use NatecSdk\Helpers;
use ReflectionNamedType;
use ReflectionProperty;

abstract class AbstractResource
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $propertyValues
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function create(array $data, array $propertyValues = []): static
    {
        $resourceClassName = static::class;

        /**
         * @var string $key
         * @var string|int|float|boolean|null $value
         */
        foreach ($data as $key => $value) {
            $property = Helpers::toCamelCase($key);

            if (property_exists($resourceClassName, $property)) {
                // @phpstan-ignore-next-line
                $propertyType = (new ReflectionProperty($resourceClassName, $property))->getType();

                if ($propertyType instanceof ReflectionNamedType && !$propertyType->isBuiltin()) {
                    $propertyTypeClassName = $propertyType->getName();

                    if ($propertyTypeClassName === DateTimeImmutable::class) {
                        $propertyValues[$property] = Helpers::castDateTime((string)$value);

                        continue;
                    }

                    $classImplements = class_implements($propertyTypeClassName);
                    if ($classImplements !== false && in_array(BackedEnum::class, $classImplements)) {
                        $propertyValues[$property] = $propertyTypeClassName::from($value);

                        continue;
                    }
                }

                if ($propertyType?->allowsNull() === true && is_string($value) && strlen($value) < 1) {
                    $value = null;
                }

                $propertyValues[$property] = $value;
            }
        }

        return new static(...$propertyValues);
    }
}
