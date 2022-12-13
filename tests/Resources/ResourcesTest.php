<?php declare(strict_types=1);

namespace NatecSdk\Tests\Resources;

use NatecSdk\Resources\Invoice;
use NatecSdk\Resources\Resource;
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
    public function dataProvider(): array
    {
        return [
            'AssortmentUpdate' => require __DIR__ . '/_data/assortment-updates.php',
            'FlashData' => require __DIR__ . '/_data/flashdata.php',
            'Invoice' => require __DIR__ . '/_data/invoices.php',
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param class-string<Resource> $resourceClass
     * @param array<string, mixed> $data
     * @param Resource $expected
     * @return void
     */
    public function testResources(string $resourceClass, array $data, Resource $expected): void
    {
        $this->assertEquals($expected, new $resourceClass($data));
    }

    /**
     * @uses \NatecSdk\Resources\Invoice
     */
    public function testNoticeForUnknownProperty(): void
    {
        $data = require __DIR__ . '/_data/invoices.php';

        $data['data']['newDataPoint'] = true;

        $this->expectNotice();
        $this->expectNoticeMessage('Unknown property newDataPoint for resource NatecSdk\Resources\Invoice.');

        $this->assertEquals($data['expected'], new Invoice($data['data']));
    }
}
