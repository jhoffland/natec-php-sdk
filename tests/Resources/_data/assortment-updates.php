<?php declare(strict_types=1);

use NatecSdk\Resources\AssortmentUpdate;
use NatecSdk\Resources\Types\AssortmentUpdateType;
use NatecSdk\Tests\TestHelpers;

$data = [
    'id'                 => 45807,
    'productNumber'      => '1151-0350',
    'productDescription' => 'Trina Solar Mono Black Frame VERTEX S TSM-420 DE09R.08 | 144C | 9BB | 30 mm',
    'newValue'           => '€11,11',
    'oldValue'           => '',
    'type'               => 'product_add',
    'updatedAt'          => '2022-12-20',
];

$assortmentUpdate = new AssortmentUpdate(
    id: $data['id'],
    productNumber: $data['productNumber'],
    productDescription: $data['productDescription'],
    newValue: $data['newValue'],
    oldValue: $data['oldValue'],
    type: AssortmentUpdateType::PRODUCT_ADD,
    updatedAt: TestHelpers::createDateTime('2022-12-20T00:00:00.000'),
);


return [
    'resourceClass' => AssortmentUpdate::class,
    'data'          => $data,
    'expected'      => $assortmentUpdate,
];
