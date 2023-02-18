<?php declare(strict_types=1);

use NatecSdk\Resources\Invoice;

return Invoice::create([
    'postingDate'     => '2022-07-20T00:00:00',
    'documentNo'      => 'GVFN22-11122',
    'description'     => 'Order VON22-12345',
    'amount'          => 133.09999999999999,
    'remainingAmount' => 33.09999999999999,
]);
