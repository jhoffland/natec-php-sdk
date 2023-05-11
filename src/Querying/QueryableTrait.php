<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use NatecSdk\Client;

trait QueryableTrait
{
    /**
     * @param array<string, string> $query
     * @param positive-int $startPage
     * @param positive-int $pageSize
     * @return \NatecSdk\Querying\ResultSet<static>
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function get(
        Client $client,
        array $query = [],
        int $startPage = 1,
        int $pageSize = 25,
    ): ResultSet {
        return new ResultSet(static::class, $client, $query, $startPage, $pageSize);
    }

    abstract public static function endpoint(): string;
}
