<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;
use NatecSdk\Querying\Queryable;

class Order extends Resource
{
    use Queryable;

    public const NOTICE_FOR_MISSING_PROPERTY = false;

    public string $no;
    public ?string $quoteNo;
    public DateTimeInterface $documentDate;
    public DateTimeInterface $postingDate;
    public DateTimeInterface $orderDate;
    public DateTimeInterface $dueDate;
    public DateTimeInterface $requestedDeliveryDate;
    public string $externalDocumentNo;
    public string $yourReference;
    public bool $pricesIncludingVat;
    public string $paymentTermsCode;
    public string $shipToCode;
    public string $shipToName;
    public string $shipToAddress;
    public string $shipToPostCode;
    public string $shipToCity;
    public DateTimeInterface $shipmentDate;
    public string $shippingTime;

    /** @var array<\NatecSdk\Resources\OrderLine> */
    public array $lines = [];

    /**
     * @param array<string, mixed> $data
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function __construct(array $data)
    {
        /** @var array<array<string, mixed>> $lines */
        $lines = $data['lines'];

        foreach ($lines as $line) {
            $this->lines[] = new OrderLine($line);
        }

        unset($data['lines']);

        parent::__construct($data);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function endpoint(): string
    {
        return '/orders';
    }
}
