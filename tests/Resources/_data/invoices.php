<?php declare(strict_types=1);

use NatecSdk\Helpers;
use NatecSdk\Resources\Invoice;
use NatecSdk\Tests\TestHelpers;

$data = [
    'postingDate' => '2022-12-20T00:00:00',
    'documentNo' => 'GVFN22-12345',
    'description' => 'Order VON22-12345',
    'amount' => 111.099999999999,
    'remainingAmount' => 22.23,
];

$invoice = new Invoice([]);
$invoice->documentNo = $data['documentNo'];
$invoice->postingDate = TestHelpers::createDateTime('2022-12-20T00:00:00.000');
$invoice->description = $data['description'];
$invoice->amount = $data['amount'];
$invoice->remainingAmount = $data['remainingAmount'];


return [
    'resourceClass' => Invoice::class,
    'data' => $data,
    'expected' => $invoice
];
