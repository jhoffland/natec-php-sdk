<?php declare(strict_types=1);

use NatecSdk\Resources\FlashData;

$data = [
    'itemNo' => '1121-0405',
    'documentNo' => 'GVZ22-12345',
    'serialNo' => '1211111111',
    'containerNr' => '987654321',
    'palletNo' => '123456789',
    'iscA' => '10.94',
    'vocV' => '41.01',
    'impA' => '10.58',
    'vmpV' => '34.53',
    'pmaxW' => '365.40'
];

$flashData = new FlashData([]);
$flashData->itemNo = $data['itemNo'];
$flashData->documentNo = $data['documentNo'];
$flashData->serialNo = $data['serialNo'];
$flashData->containerNr = $data['containerNr'];
$flashData->palletNo = $data['palletNo'];
$flashData->iscA = $data['iscA'];
$flashData->vocV = $data['vocV'];
$flashData->impA = $data['impA'];
$flashData->vmpV = $data['vmpV'];
$flashData->pmaxW = $data['pmaxW'];

return [
    'resourceClass' => FlashData::class,
    'data' => $data,
    'expected' => $flashData
];
