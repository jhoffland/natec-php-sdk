<?php declare(strict_types=1);

namespace NatecSdk\Resources;

class FlashData extends AbstractResource
{
    final public function __construct(
        public readonly string $itemNo,
        public readonly string $documentNo,
        public readonly ?string $serialNo,
        public readonly ?string $containerNr,
        public readonly ?string $palletNo,
        public readonly ?float $iscA,
        public readonly ?float $vocV,
        public readonly ?float $impA,
        public readonly ?float $vmpV,
        public readonly ?float $pmaxW,
    ) {
    }
}
