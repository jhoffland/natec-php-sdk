<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;
use NatecSdk\Client;
use NatecSdk\Querying\Findable;
use NatecSdk\Querying\Queryable;

class Shipment extends Resource
{
    use Queryable, Findable;

    public string $no;
    public string $sellToCustomerName;
    public string $sellToAddress;
    public string $sellToCity;
    public string $sellToPostCode;
    public string $sellToContact;
    public ?string $sellToCountry;
    public DateTimeInterface $requestedDeliveryDate;
    public string $orderNo;
    public string $shipToName;
    public string $shipToAddress;
    public string $shipToCity;
    public string $shipToCountry;
    public string $shipToPostCode;
    public string $shipToContact;
    public DateTimeInterface $shipmentDate;

    /** @var array<\NatecSdk\Resources\ShipmentLine> */
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
            $this->lines[] = new ShipmentLine($line);
        }

        unset($data['lines']);

        parent::__construct($data);
    }

    /**
     * @param \NatecSdk\Client $client
     * @param array<string, string> $query
     * @return array<\NatecSdk\Resources\FlashData>
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function flashData(Client $client, array $query = []): array
    {
        $endpoint = sprintf('%s/%s/flashdata', static::endpoint(), $this->no);

        /** @var array<array<string, mixed>> $data */
        $data = $client->get($endpoint, $query);

        $flashData = [];

        foreach ($data as $dataPerResource) {
            $flashData[] = new FlashData($dataPerResource);
        }

        return $flashData;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function endpoint(): string
    {
        return '/shipments';
    }
}
