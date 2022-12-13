<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use NatecSdk\Client;

trait Queryable
{
    /**
     * @param \NatecSdk\Client $client
     * @param array<string, string> $query
     * @param positive-int $startPage
     * @param positive-int $pageSize
     * @return \NatecSdk\Querying\ResultSet
     */
    public static function get(
        Client $client,
        array $query = [],
        int $startPage = 1,
        int $pageSize = 25
    ): ResultSet {
        return new ResultSet(static::class, $client, $query, $startPage, $pageSize);
    }

    /**
     * @codeCoverageIgnore
     */
    abstract public static function endpoint(): string;
}
