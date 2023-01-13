<?php declare(strict_types=1);

namespace NatecSdk\Tests;

use DateTime;
use NatecSdk\Exceptions\NatecSdkException;
use NatecSdk\Helpers;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: \DateTime}>
     */
    public function dataProviderCastDateTimeValid(): array
    {
        return [ // @phpstan-ignore-line
            'Only date' => [
                '2022-12-20',
                DateTime::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T00:00:00.000')
            ],
            'Date and Time seconds' => [
                '2022-12-20T13:03:29',
                DateTime::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T13:03:29.000')
            ],
            'Date and Time milliseconds' => [
                '2022-12-20T13:03:29.300',
                DateTime::createFromFormat('Y-m-d\TH:i:s.v', '2022-12-20T13:03:29.300')
            ]
        ];
    }

    /**
     * @dataProvider dataProviderCastDateTimeValid
     *
     * @param string $dateTimeString
     * @param \DateTime $expectedDateTime
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function testCastDateTimeValid(string $dateTimeString, DateTime $expectedDateTime): void
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
