<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;

class ShipmentLine extends AbstractResource
{
    final public function __construct(
        public readonly string $documentNo,
        public readonly string $lineNo,
        public readonly string $no,
        public readonly string $description,
        public readonly float $quantityInvoiced,
        public readonly DateTimeImmutable $requestedDeliveryDate,
        public readonly DateTimeImmutable $shipmentDate,
    ) {
    }
}
