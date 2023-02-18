<?php declare(strict_types=1);

use NatecSdk\Resources\FlashData;

$data = [
    'itemNo'      => '1121-0405',
    'documentNo'  => 'GVZ22-12345',
    'serialNo'    => '1211111111',
    'containerNr' => '987654321',
    'palletNo'    => '123456789',
    'iscA'        => '10.94',
    'vocV'        => '41.01',
    'impA'        => '10.58',
    'vmpV'        => '34.53',
    'pmaxW'       => '365.40',
];

$flashData = new FlashData(
    itemNo: $data['itemNo'],
    documentNo: $data['documentNo'],
    serialNo: $data['serialNo'],
    containerNr: $data['containerNr'],
    palletNo: $data['palletNo'],
    iscA: $data['iscA'],
    vocV: $data['vocV'],
    impA: $data['impA'],
    vmpV: $data['vmpV'],
    pmaxW: $data['pmaxW'],
);

return [
    'resourceClass' => FlashData::class,
    'data'          => $data,
    'expected'      => $flashData,
];
