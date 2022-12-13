<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use NatecSdk\Client;
use NatecSdk\Exceptions\NatecSdkException;

trait Findable
{
    /**
     * @param array<string, mixed> $data
     */
    abstract public function __construct(array $data);

    /**
     * @param \NatecSdk\Client $client
     * @param int|string $id
     * @return static
     */
    public static function find(Client $client, int|string $id): static
    {
        $endpoint = sprintf('%s/%s', static::endpoint(), $id);

        return new static($client->get($endpoint));
    }

    /**
     * @codeCoverageIgnore
     */
    abstract public static function endpoint(): string;
}
