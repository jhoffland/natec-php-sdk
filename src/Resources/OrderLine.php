<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;

class OrderLine extends AbstractResource
{
    final public function __construct(
        public readonly string $documentType,
        public readonly string $documentNo,
        public readonly int $lineNo,
        public readonly string $type,
        public readonly string $no,
        public readonly bool $nonstock,
        public readonly string $vatProdPostingGroup,
        public readonly string $description,
        public readonly float $quantity,
        public readonly string $unitOfMeasureCode,
        public readonly string $unitOfMeasure,
        public readonly float $unitPrice,
        public readonly float $lineDiscountPercent,
        public readonly float $lineAmount,
        public readonly float $lineDiscountAmount,
        public readonly DateTimeImmutable $requestedDeliveryDate,
        public readonly ?DateTimeImmutable $promisedDeliveryDate,
        public readonly DateTimeImmutable $plannedDeliveryDate,
        public readonly DateTimeImmutable $plannedShipmentDate,
        public readonly DateTimeImmutable $shipmentDate,
        public readonly string $shippingTime,
        public readonly int $qtyPerUnitOfMeasure,
        public readonly int $vatPercent,
    ) {
    }
}
