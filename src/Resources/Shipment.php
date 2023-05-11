<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Client;
use NatecSdk\Querying\FindableTrait;
use NatecSdk\Querying\QueryableTrait;

class Shipment extends AbstractResource
{
    use QueryableTrait, FindableTrait;

    /**
     * @param array<\NatecSdk\Resources\ShipmentLine> $lines
     */
    final public function __construct(
        public readonly string $no,
        public readonly string $sellToCustomerName,
        public readonly string $sellToAddress,
        public readonly string $sellToCity,
        public readonly string $sellToPostCode,
        public readonly string $sellToContact,
        public readonly ?string $sellToCountry,
        public readonly DateTimeImmutable $requestedDeliveryDate,
        public readonly string $orderNo,
        public readonly string $shipToName,
        public readonly string $shipToAddress,
        public readonly string $shipToCity,
        public readonly ?string $shipToCountry,
        public readonly string $shipToPostCode,
        public readonly string $shipToContact,
        public readonly DateTimeImmutable $shipmentDate,
        public readonly array $lines = [],
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        $propertyValues['lines'] = [];

        /** @var array<array<string, mixed>> $lines */
        $lines = $data['lines'];

        foreach ($lines as $line) {
            if (!isset($line['no'])) {
                continue;
            }

            $propertyValues['lines'][] = ShipmentLine::create($line);
        }

        unset($data['lines']);

        return parent::create($data, $propertyValues);
    }

    /**
     * @param array<string, string> $query
     * @return iterable<\NatecSdk\Resources\FlashData>
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function flashData(Client $client, array $query = []): iterable
    {
        $endpoint = sprintf('%s/%s/flashdata', self::endpoint(), $this->no);

        /** @var array<array<string, mixed>> $data */
        $data = $client->get($endpoint, $query);

        foreach ($data as $dataPerResource) {
            yield FlashData::create($dataPerResource);
        }
    }

    public static function endpoint(): string
    {
        return '/shipments';
    }
}
