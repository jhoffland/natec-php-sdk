<?php declare(strict_types=1);

namespace NatecSdk\Resources;

class Price extends Resource
{
    public int $vpe;
    public string $vpeName;
    public string $grossPrice;
    public string $netPrice;
    public string $discount;
    public string $brutoPriceWattpiek;
    public string $nettoPriceWattpiek;
}
