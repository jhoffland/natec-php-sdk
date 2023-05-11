<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Client;
use NatecSdk\Querying\QueryableTrait;

class Order extends AbstractResource
{
    use QueryableTrait;

    /**
     * @param array<\NatecSdk\Resources\OrderLine> $lines
     */
    final public function __construct(
        public readonly string $documentType,
        public readonly string $no,
        public readonly string $sellToCustomerNo,
        public readonly string $sellToCustomerName,
        public readonly ?string $quoteNo,
        public readonly string $sellToContactNo,
        public readonly string $sellToContact,
        public readonly int $noOfArchivedVersions,
        public readonly DateTimeImmutable $documentDate,
        public readonly DateTimeImmutable $postingDate,
        public readonly DateTimeImmutable $orderDate,
        public readonly DateTimeImmutable $dueDate,
        public readonly DateTimeImmutable $requestedDeliveryDate,
        public readonly ?string $externalDocumentNo,
        public readonly string $yourReference,
        public readonly bool $pricesIncludingVat,
        public readonly string $vatBusPostingGroup,
        public readonly string $paymentTermsCode,
        public readonly ?string $shipToCode,
        public readonly string $shipToName,
        public readonly string $shipToAddress,
        public readonly string $shipToCity,
        public readonly string $shipToPostCode,
        public readonly string $billToCustomerNo,
        public readonly string $billToName,
        public readonly string $billToContactNo,
        public readonly string $billToContact,
        public readonly DateTimeImmutable $shipmentDate,
        public readonly string $shippingTime,
        public readonly array $lines,
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        $propertyValues['lines'] = [];

        /** @var array<array<string, mixed>> $linesData */
        $linesData = $data['lines'];

        foreach ($linesData as $line) {
            $propertyValues['lines'][] = OrderLine::create($line);
        }

        unset($data['lines']);

        return parent::create($data, $propertyValues);
    }

    /**
     * @param \Psr\Http\Message\StreamInterface|resource|string $destination
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function confirmation(Client $client, $destination): void
    {
        $endpoint = sprintf('order/%s/confirmation', $this->no);

        $client->getPdf($endpoint, $destination);
    }

    public static function endpoint(): string
    {
        return '/orders';
    }
}
