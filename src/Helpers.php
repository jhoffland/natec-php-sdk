<?php declare(strict_types=1);

namespace NatecSdk;

use DateTime;
use Exception;
use NatecSdk\Exceptions\NatecSdkException;

class Helpers
{
    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function castDateTime(string $dateTime): DateTime
    {
        try {
            return new DateTime($dateTime);
        } catch (Exception $exception) {
            throw new NatecSdkException('Invalid date (time) string: ' . $dateTime, 0, $exception);
        }
    }
}
