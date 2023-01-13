<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;

class ShipmentLine extends Resource
{
    public string $documentNo;
    public string $lineNo;
    public string $no;
    public string $description;
    public int $quantityInvoiced;
    public DateTimeInterface $requestedDeliveryDate;
    public DateTimeInterface $shipmentDate;
}
