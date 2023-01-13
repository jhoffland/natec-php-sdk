<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;

class OrderLine extends Resource
{
    public const NOTICE_FOR_MISSING_PROPERTY = false;

    public string $documentNo;
    public int $lineNo;
    public string $type;
    public string $no;
    public bool $nonstock;
    public string $description;
    public float $quantity;
    public string $unitOfMeasureCode;
    public float $unitPrice;
    public float $lineDiscountPercent;
    public float $lineAmount;
    public float $lineDiscountAmount;
    public DateTimeInterface $requestedDeliveryDate;
    public DateTimeInterface $promisedDeliveryDate;
    public DateTimeInterface $plannedDeliveryDate;
    public DateTimeInterface $plannedShipmentDate;
    public DateTimeInterface $shipmentDate;
    public string $shippingTime;
    public int $vatPercent;
}
