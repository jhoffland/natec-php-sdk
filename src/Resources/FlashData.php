<?php declare(strict_types=1);

namespace NatecSdk\Resources;

class FlashData extends Resource
{
    final public function __construct(
        public readonly string $itemNo,
        public readonly string $documentNo,
        public readonly string $serialNo,
        public readonly string $containerNr,
        public readonly string $palletNo,
        public readonly string $iscA,
        public readonly string $vocV,
        public readonly string $impA,
        public readonly string $vmpV,
        public readonly string $pmaxW,
    ) {
    }
}
