<?php declare(strict_types=1);

namespace NatecSdk\Tests;

use DateTimeImmutable;
use DateTimeZone;
use NatecSdk\Exceptions\NatecSdkException;
use NatecSdk\Helpers;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: \DateTime}>
     */
    public static function dataProviderCastDateTimeValid(): array
    {
        $timeZone = new DateTimeZone('Europe/Amsterdam');

        return [ // @phpstan-ignore-line
            'Only date'                  => [
                '2022-12-20',
                DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T00:00:00.000', $timeZone),
            ],
            'Date and Time seconds'      => [
                '2022-12-20T13:03:29',
                DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T13:03:29.000', $timeZone),
            ],
            'Date and Time milliseconds' => [
                '2022-12-20T13:03:29.300',
                DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T13:03:29.300', $timeZone),
            ],
        ];
    }

    #[DataProvider('dataProviderCastDateTimeValid')]
    public function testCastDateTimeValid(string $dateTimeString, DateTimeImmutable $expectedDateTime): void
    {
        $this->assertEquals($expectedDateTime, Helpers::castDateTime($dateTimeString));
    }

    public function testCastDateTimeInvalid(): void
    {
        $this->expectException(NatecSdkException::class);
        $this->expectExceptionMessage('Invalid date (time) string: TestDateTimeString');

        Helpers::castDateTime('TestDateTimeString');
    }

    public function testToCamelCase() : void
    {
        $this->assertEquals('documentType', Helpers::toCamelCase('document_type'));
    }
}
