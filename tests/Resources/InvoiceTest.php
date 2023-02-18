<?php declare(strict_types=1);

namespace NatecSdk\Tests\Resources;

use NatecSdk\Resources\Invoice;
use NatecSdk\Resources\Types\InvoiceType;
use NatecSdk\Tests\TestHelpers;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @return array<string, array{documentNo: string, expectedType: \NatecSdk\Resources\Types\InvoiceType|null}>
     */
    public static function typeDataProvider(): array
    {
        return [
            'Regular invoice type'    => [
                'documentNo'   => 'GVFN22-12345',
                'expectedType' => InvoiceType::REGULAR,
            ],
            'Prepayment invoice type' => [
                'documentNo'   => 'GVBF22-12345',
                'expectedType' => InvoiceType::PREPAYMENT,
            ],
            'Credit invoice type'     => [
                'documentNo'   => 'GCNV22-12345',
                'expectedType' => InvoiceType::CREDIT,
            ],
            'Unknown invoice type'    => [
                'documentNo'   => 'ML22-1234',
                'expectedType' => null,
            ],
            'Unknown invoice type 2'  => [
                'documentNo'   => 'ING07-23-017',
                'expectedType' => null,
            ],
        ];
    }

    #[DataProvider('typeDataProvider')]
    public function testType(string $documentNo, ?InvoiceType $expectedType): void
    {
        $invoice = new Invoice(
            documentNo: $documentNo,
            postingDate: TestHelpers::createDateTime('2023-02-10T14:00:00.000'),
            description: 'Order VON22-12345',
            amount: 12345.67,
            remainingAmount: 1234.56,
        );

        $this->assertEquals($expectedType, $invoice->type());
    }
}
