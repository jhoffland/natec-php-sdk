<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;
use NatecSdk\Querying\Findable;
use NatecSdk\Querying\Queryable;

class Invoice extends Resource
{
    use Queryable, Findable;

    public string $documentNo;
    public DateTimeInterface $postingDate;
    public string $description;
    public float $amount;
    public float $remainingAmount;

    /**
     * @codeCoverageIgnore
     */
    public static function endpoint(): string
    {
        return '/invoices';
    }
}
