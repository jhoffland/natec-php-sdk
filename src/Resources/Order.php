<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Querying\Queryable;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class Order extends Resource
{
    use Queryable;

    public const NOTICE_FOR_MISSING_PROPERTY = false;

    /**
     * @param array<\NatecSdk\Resources\OrderLine> $lines
     */
    final public function __construct(
        public readonly string $no,
        public readonly ?string $quoteNo,
        public readonly DateTimeImmutable $documentDate,
        public readonly DateTimeImmutable $postingDate,
        public readonly DateTimeImmutable $orderDate,
        public readonly DateTimeImmutable $dueDate,
        public readonly DateTimeImmutable $requestedDeliveryDate,
        public readonly string $externalDocumentNo,
        public readonly string $yourReference,
        public readonly bool $pricesIncludingVat,
        public readonly string $paymentTermsCode,
        public readonly string $shipToCode,
        public readonly string $shipToName,
        public readonly string $shipToAddress,
        public readonly string $shipToPostCode,
        public readonly string $shipToCity,
        public readonly DateTimeImmutable $shipmentDate,
        public readonly string $shippingTime,
        public readonly array $lines,
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        $propertyValues['lines'] = [];

        /** @var array<array<string, mixed>> $lines */
        $lines = $data['lines'];

        foreach ($lines as $line) {
            $propertyValues['lines'][] = OrderLine::create($line);
        }

        unset($data['lines']);

        return parent::create($data, $propertyValues);
    }

    #[CodeCoverageIgnore]
    public static function endpoint(): string
    {
        return '/orders';
    }
}
