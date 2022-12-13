<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTime;
use NatecSdk\Exceptions\NatecSdkException;
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
        foreach ($data as $property => $value) {
            if (property_exists(static::class, $property)) {
                $propertyType = (new ReflectionProperty(static::class, $property))->getType()?->__toString();

                if ($propertyType === DateTime::class) {
                    /** @var string $value */
                    $this->{$property} = Helpers::castDateTime($value);
//                } elseif ($propertyType === 'float' && gettype($value) === 'string' && is_numeric($value)) {
//                    $this->{$property} = (float)$value;
                } else {
                    $this->{$property} = $value;
                }
            } else {
                trigger_error(sprintf('Unknown property %s for resource %s.', $property, static::class), E_USER_NOTICE);
            }
        }
    }
}
