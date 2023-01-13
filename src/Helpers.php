<?php declare(strict_types=1);

namespace NatecSdk;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use NatecSdk\Exceptions\NatecSdkException;

class Helpers
{
    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function castDateTime(string $dateTime): DateTimeInterface
    {
        try {
            return new DateTime($dateTime, self::apiTimeZone());
        } catch (Exception $exception) {
            throw new NatecSdkException('Invalid date (time) string: ' . $dateTime, 0, $exception);
        }
    }

    public static function apiTimeZone() : DateTimeZone
    {
        return new DateTimeZone('UTC');
    }

    public static function toCamelCase(string $value) : string
    {
        $words = explode(' ', str_replace(['_', '-'], ' ', $value));
        $words = array_map(static fn(string $word) => ucfirst($word), $words);

        return lcfirst(implode($words));
    }
}
