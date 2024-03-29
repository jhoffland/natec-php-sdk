<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use NatecSdk\Client;

trait FindableTrait
{
    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function find(Client $client, int|string $id): ?static
    {
        $endpoint = sprintf('%s/%s', static::endpoint(), $id);

        $data = $client->get($endpoint);

        if ($data === null) {
            return null;
        }

        return static::create($data);
    }

    abstract public static function endpoint(): string;
}
