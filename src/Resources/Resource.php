<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;
use NatecSdk\Helpers;
use ReflectionProperty;

/** @phpstan-consistent-constructor */
abstract class Resource
{
    /**
     * @param array<string, mixed> $data
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function __construct(array $data)
    {
        $noticeForMissingProperty = !defined(static::class . '::NOTICE_FOR_MISSING_PROPERTY')
            || static::NOTICE_FOR_MISSING_PROPERTY; // @phpstan-ignore-line

        foreach ($data as $key => $value) {
            $property = Helpers::toCamelCase($key);

            if (property_exists(static::class, $property)) {
                $propertyType = (new ReflectionProperty(static::class, $property))->getType()?->__toString();

                if ($propertyType === DateTimeInterface::class) {
                    /** @var string $value */
                    $this->{$property} = Helpers::castDateTime($value);
                } else {
                    $this->{$property} = $value;
                }
            } elseif ($noticeForMissingProperty) {
                trigger_error(sprintf('Unknown property %s for resource %s', $property, static::class), E_USER_NOTICE);
            }
        }
    }
}
