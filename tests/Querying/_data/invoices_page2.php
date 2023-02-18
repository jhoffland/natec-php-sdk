<?php declare(strict_types=1);

use NatecSdk\Resources\Invoice;

return [
    Invoice::create([
        'postingDate'     => '2022-07-16T00:00:00',
        'documentNo'      => 'GVFN22-09345',
        'description'     => 'Order VON22-12098',
        'amount'          => 202,
        'remainingAmount' => 202,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-15T00:00:00',
        'documentNo'      => 'GVFN22-09876',
        'description'     => 'Order VON22-45212',
        'amount'          => 67.969999999999999,
        'remainingAmount' => 9,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-15T00:00:00',
        'documentNo'      => 'GVFN22-57382',
        'description'     => 'Order VON22-01895',
        'amount'          => 681.75,
        'remainingAmount' => 681.75,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-15T00:00:00',
        'documentNo'      => 'GVFN22-7892',
        'description'     => 'Order VON22-129076',
        'amount'          => 2700.4499999999998,
        'remainingAmount' => 2700.4499999999998,
    ]),
    Invoice::create([
        'postingDate'     => '2022-07-15T00:00:00',
        'documentNo'      => 'GVFN22-19854',
        'description'     => 'Order VON22-83626',
        'amount'          => 5682.7200000000003,
        'remainingAmount' => 5682.7200000000003,
    ]),
];
