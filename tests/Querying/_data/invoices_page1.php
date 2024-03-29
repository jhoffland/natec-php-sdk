<?php declare(strict_types=1);

use NatecSdk\Resources\Invoice;

return [
    Invoice::create([
        'postingDate'     => '2022-07-20T00:00:00',
        'documentNo'      => 'GVFN22-11122',
        'description'     => 'Order VON22-12345',
        'amount'          => 133.09999999999999,
        'remainingAmount' => 33.09999999999999,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-20T00:00:00',
        'documentNo'      => 'GVFN22-11222',
        'description'     => 'Order VON22-12456',
        'amount'          => 61.123567,
        'remainingAmount' => 64.129999999999995,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-20T00:00:00',
        'documentNo'      => 'GVFN22-11133',
        'description'     => 'Order VON22-12890',
        'amount'          => 11,
        'remainingAmount' => 0,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-20T00:00:00',
        'documentNo'      => 'GVFN22-27452',
        'description'     => 'Order VON22-24436',
        'amount'          => 50.82,
        'remainingAmount' => 50.82,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-18T00:00:00',
        'documentNo'      => 'ING07-22-123',
        'description'     => 'Deel factuur: GVFN22-7683',
        'amount'          => -938.04,
        'remainingAmount' => 0,
    ]),
];
