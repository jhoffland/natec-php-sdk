<?php declare(strict_types=1);

use NatecSdk\Resources\Invoice;
use NatecSdk\Tests\TestHelpers;

$data = [
    'postingDate'     => '2022-12-20T00:00:00',
    'documentNo'      => 'GVFN22-12345',
    'description'     => 'Order VON22-12345',
    'amount'          => 111.099999999999,
    'remainingAmount' => 22.23,
];

$invoice = new Invoice(
    documentNo: $data['documentNo'],
    postingDate: TestHelpers::createDateTime('2022-12-20T00:00:00.000'),
    description: $data['description'],
    amount: $data['amount'],
    remainingAmount: $data['remainingAmount'],
);

return [
    'resourceClass' => Invoice::class,
    'data'          => $data,
    'expected'      => $invoice,
];
