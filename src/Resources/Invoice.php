<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Querying\Findable;
use NatecSdk\Querying\Queryable;
use NatecSdk\Resources\Types\InvoiceType;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class Invoice extends Resource
{
    use Queryable, Findable;

    final public function __construct(
        public readonly string $documentNo,
        public readonly DateTimeImmutable $postingDate,
        public readonly string $description,
        public readonly float $amount,
        public readonly float $remainingAmount,
    ) {
    }

    public function type(): ?InvoiceType
    {
        return InvoiceType::fromInvoiceNo($this->documentNo);
    }

    #[CodeCoverageIgnore]
    public static function endpoint(): string
    {
        return '/invoices';
    }
}
