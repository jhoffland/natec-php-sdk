<?php declare(strict_types=1);

namespace NatecSdk\Tests;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

class TestHelpers
{
    public static function createDateTime(string $datetime) : DateTimeInterface
    {
        return DateTime::createFromFormat( // @phpstan-ignore-line
            'Y-m-d\TH:i:s.v',
            $datetime,
            new DateTimeZone('UTC')
        );
    }
}
