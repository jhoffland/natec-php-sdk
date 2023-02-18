<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Client;
use NatecSdk\Querying\Findable;
use NatecSdk\Querying\Queryable;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class Shipment extends Resource
{
    use Queryable, Findable;

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
        public readonly string $shipToCountry,
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
            $propertyValues['lines'][] = ShipmentLine::create($line);
        }

        unset($data['lines']);

        return parent::create($data, $propertyValues);
    }

    /**
     * @param array<string, string> $query
     * @return array<\NatecSdk\Resources\FlashData>
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function flashData(Client $client, array $query = []): array
    {
        $endpoint = sprintf('%s/%s/flashdata', self::endpoint(), $this->no);

        /** @var array<array<string, mixed>> $data */
        $data = $client->get($endpoint, $query);

        $flashData = [];

        foreach ($data as $dataPerResource) {
            $flashData[] = FlashData::create($dataPerResource);
        }

        return $flashData;
    }

    #[CodeCoverageIgnore]
    public static function endpoint(): string
    {
        return '/shipments';
    }
}
