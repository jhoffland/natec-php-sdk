<?php declare(strict_types=1);

namespace NatecSdk\Resources;

class Price extends AbstractResource
{
    final public function __construct(
        public readonly int $vpe,
        public readonly string $vpeName,
        public readonly string $grossPrice,
        public readonly string $netPrice,
        public readonly string $discount,
        public readonly string $brutoPriceWattpiek,
        public readonly string $nettoPriceWattpiek,
    ) {
    }
}
