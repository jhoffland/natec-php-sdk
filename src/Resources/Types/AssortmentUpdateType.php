<?php declare(strict_types=1);

namespace NatecSdk\Resources\Types;

enum AssortmentUpdateType: string
{
    case PRODUCT_ADD = 'product_add';
    case PRICE_UPDATE = 'price_update';
    case PRODUCT_DISCONTINUED = 'product_discontinued';
    case PRODUCT_EXPECTED_ARRIVAL = 'product_expected_arrival';
    case PRODUCT_REMOVE = 'product_remove';
}
