<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Querying\FindableTrait;
use NatecSdk\Querying\QueryableTrait;
use NatecSdk\Resources\Types\InvoiceType;

class Invoice extends AbstractResource
{
    use QueryableTrait, FindableTrait;

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

    public function orderNo(): ?string
    {
        $matchCount = preg_match('/^Order (?P<orderNo>VON[1-9][0-9]-[0-9]{1,})$/', $this->description, $matches);

        return $matchCount === 1 ? $matches['orderNo'] : null;
    }

    public static function endpoint(): string
    {
        return '/invoices';
    }
}
