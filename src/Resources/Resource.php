<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use BackedEnum;
use DateTimeImmutable;
use NatecSdk\Exceptions\NatecSdkException;
use NatecSdk\Helpers;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

abstract class Resource
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $propertyValues
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function create(array $data, array $propertyValues = []): static
    {
        $resourceClassName = static::class;

        $noticeForMissingProperty = !defined($resourceClassName . '::NOTICE_FOR_MISSING_PROPERTY')
            || static::NOTICE_FOR_MISSING_PROPERTY; // @phpstan-ignore-line

        /**
         * @var string $key
         * @var string|int|float|boolean|null $value
         */
        foreach ($data as $key => $value) {
            $property = Helpers::toCamelCase($key);

            if (property_exists($resourceClassName, $property)) {
                try {
                    $propertyType = (new ReflectionProperty($resourceClassName, $property))->getType();
                } catch (ReflectionException $exception) {
                    throw NatecSdkException::createFromException($exception);
                }

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

                $propertyValues[$property] = $value;
            } elseif ($noticeForMissingProperty) {
                trigger_error(sprintf('Unknown property %s for resource %s', $property, $resourceClassName));
            }
        }

        return new static(...$propertyValues);
    }
}
