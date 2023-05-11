<?php declare(strict_types=1);

namespace NatecSdk\Tests;

use DateTimeImmutable;
use DateTimeZone;

class TestHelpers
{
    public static function createDateTime(string $datetime) : DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat( // @phpstan-ignore-line
            'Y-m-d\TH:i:s.v',
            $datetime,
            new DateTimeZone('Europe/Amsterdam'),
        );
    }
}
