<?php declare(strict_types=1);

namespace NatecSdk\Tests\Resources;

use NatecSdk\Resources\Invoice;
use NatecSdk\Resources\Resource;
use NatecSdk\Tests\TestHelpers;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    /**
     * @return array<string, array{
     *     resourceClass: class-string<Resource>,
     *     data: array<string, mixed>,
     *     expected: Resource
     * }>
     */
    public static function dataProvider(): array
    {
        return [
            'AssortmentUpdate' => require __DIR__ . '/_data/assortment-updates.php',
            'FlashData'        => require __DIR__ . '/_data/flashdata.php',
            'Invoice'          => require __DIR__ . '/_data/invoices.php',
            'Order'            => require __DIR__ . '/_data/orders.php',
        ];
    }

    /**
     * @param class-string<Resource> $resourceClass
     * @param array<string, mixed> $data
     */
    #[DataProvider('dataProvider')]
    public function testResources(string $resourceClass, array $data, Resource $expected): void
    {
        $this->assertEquals($expected, $resourceClass::create(TestHelpers::shuffleArray($data)));
    }

    public function testNoticeForUnknownProperty(): void
    {
        $rightNoticeGiven = false;

        $previousErrorHandler = set_error_handler(
            static function (int $errno, string $errstr) use (&$rightNoticeGiven): bool {
                if ($errno === E_USER_NOTICE
                    && $errstr === 'Unknown property newDataPoint for resource NatecSdk\Resources\Invoice'
                ) {
                    $rightNoticeGiven = true;
                    return true;
                }

                return false;
            },
        );

        $data = require __DIR__ . '/_data/invoices.php';

        $data['data']['newDataPoint'] = true;

        $this->assertEquals($data['expected'], Invoice::create($data['data']));

        $this->assertTrue($rightNoticeGiven, 'Notice given for unknown property.');

        set_error_handler($previousErrorHandler);
    }
}
