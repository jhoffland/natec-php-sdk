<?php declare(strict_types=1);

use NatecSdk\Resources\AssortmentUpdate;

$data = [
    'id' => 45807,
    'productNumber' => '1151-0350',
    'productDescription' => 'Trina Solar Mono Black Frame VERTEX S TSM-420 DE09R.08 | 144C | 9BB | 30 mm',
    'newValue' => '€11,11',
    'oldValue' => '',
    'type' => 'product_add',
    'updatedAt' => '2022-12-20'
];

$assortmentUpdate = new AssortmentUpdate([]);
$assortmentUpdate->id = $data['id'];
$assortmentUpdate->productNumber = $data['productNumber'];
$assortmentUpdate->productDescription = $data['productDescription'];
$assortmentUpdate->newValue = $data['newValue'];
$assortmentUpdate->oldValue = $data['oldValue'];
$assortmentUpdate->type = $data['type'];
$assortmentUpdate->updatedAt = DateTime::createFromFormat( // @phpstan-ignore-line
    'Y-m-d\TH:i:s.v',
    '2022-12-20T00:00:00.000'
);

return [
    'resourceClass' => AssortmentUpdate::class,
    'data' => $data,
    'expected' => $assortmentUpdate
];
