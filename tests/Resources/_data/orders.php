<?php declare(strict_types=1);

use NatecSdk\Resources\Order;
use NatecSdk\Resources\OrderLine;
use NatecSdk\Tests\TestHelpers;

$orderLine = new OrderLine([]);
$orderLine->documentNo = 'VON23-01234';
$orderLine->lineNo = 10000;
$orderLine->type = 'Item';
$orderLine->no = '1234-5678';
$orderLine->nonstock = false;
$orderLine->description = 'Description';
$orderLine->quantity = 10;
$orderLine->unitOfMeasureCode = 'VPE100';
$orderLine->unitPrice = 234;
$orderLine->lineDiscountPercent = 20;
$orderLine->lineAmount = 1872;
$orderLine->lineDiscountAmount = 468;
$orderLine->requestedDeliveryDate = TestHelpers::createDateTime('2023-01-17T00:00:00.000');
$orderLine->promisedDeliveryDate = TestHelpers::createDateTime('0001-01-01T00:00:00.000');
$orderLine->plannedDeliveryDate = TestHelpers::createDateTime('2023-01-17T00:00:00.000');
$orderLine->plannedShipmentDate = TestHelpers::createDateTime('2023-01-16T00:00:00.000');
$orderLine->shipmentDate = TestHelpers::createDateTime('2023-01-16T00:00:00.000');
$orderLine->shippingTime = '1D';
$orderLine->vatPercent = 21;

$order = new Order([]);
$order->no = 'VON23-01234';
$order->quoteNo = '';
$order->documentDate = TestHelpers::createDateTime('2023-01-11T00:00:00.000');
$order->postingDate = TestHelpers::createDateTime('2023-01-11T00:00:00.000');
$order->orderDate = TestHelpers::createDateTime('2023-01-11T00:00:00.000');
$order->dueDate = TestHelpers::createDateTime('2023-01-25T00:00:00.000');
$order->requestedDeliveryDate = TestHelpers::createDateTime('2023-01-17T00:00:00.000');
$order->externalDocumentNo = 'BST98123';
$order->yourReference = 'stock';
$order->pricesIncludingVat = false;
$order->paymentTermsCode = '14D';
$order->shipToCode = '';
$order->shipToName = 'Smith Ltd.';
$order->shipToAddress = 'Street 1';
$order->shipToPostCode = '1234 AB';
$order->shipToCity = 'City';
$order->shipmentDate = TestHelpers::createDateTime('2023-01-16T00:00:00.000');
$order->shippingTime = '1D';
$order->lines = [ $orderLine ];

return [
    'resourceClass' => Order::class,
    'data' => json_decode(file_get_contents(__DIR__ . '/orders.json'), true), // @phpstan-ignore-line
    'expected' => $order
];
