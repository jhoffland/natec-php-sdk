<?php declare(strict_types=1);

use NatecSdk\Resources\Order;
use NatecSdk\Resources\OrderLine;
use NatecSdk\Tests\TestHelpers;

$orderLine = new OrderLine(
    documentNo: 'VON23-01234',
    lineNo: 10000,
    type: 'Item',
    no: '1234-5678',
    nonstock: false,
    description: 'Description',
    quantity: 10,
    unitOfMeasureCode: 'VPE100',
    unitPrice: 234,
    lineDiscountPercent: 20,
    lineAmount: 1872,
    lineDiscountAmount: 468,
    requestedDeliveryDate: TestHelpers::createDateTime('2023-01-17T00:00:00.000'),
    promisedDeliveryDate: TestHelpers::createDateTime('0001-01-01T00:00:00.000'),
    plannedDeliveryDate: TestHelpers::createDateTime('2023-01-17T00:00:00.000'),
    plannedShipmentDate: TestHelpers::createDateTime('2023-01-16T00:00:00.000'),
    shipmentDate: TestHelpers::createDateTime('2023-01-16T00:00:00.000'),
    shippingTime: '1D',
    vatPercent: 21,
);

$order = new Order(
    no: 'VON23-01234',
    quoteNo: '',
    documentDate: TestHelpers::createDateTime('2023-01-11T00:00:00.000'),
    postingDate: TestHelpers::createDateTime('2023-01-11T00:00:00.000'),
    orderDate: TestHelpers::createDateTime('2023-01-11T00:00:00.000'),
    dueDate: TestHelpers::createDateTime('2023-01-25T00:00:00.000'),
    requestedDeliveryDate: TestHelpers::createDateTime('2023-01-17T00:00:00.000'),
    externalDocumentNo: 'BST98123',
    yourReference: 'stock',
    pricesIncludingVat: false,
    paymentTermsCode: '14D',
    shipToCode: '',
    shipToName: 'Smith Ltd.',
    shipToAddress: 'Street 1',
    shipToPostCode: '1234 AB',
    shipToCity: 'City',
    shipmentDate: TestHelpers::createDateTime('2023-01-16T00:00:00.000'),
    shippingTime: '1D',
    lines: [$orderLine],
);

return [
    'resourceClass' => Order::class,
    'data'          => json_decode(file_get_contents(__DIR__ . '/orders.json'), true), // @phpstan-ignore-line
    'expected'      => $order,
];
