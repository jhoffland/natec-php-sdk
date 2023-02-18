<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use Iterator;
use NatecSdk\Client;
use NatecSdk\Resources\Resource;

/**
 * @template T of Resource
 *
 * @implements \Iterator<integer, T>
 */
class ResultSet implements Iterator
{
    /** @var array<T> */
    private array $retrievedResources = [];

    private int $currentKey = 0;

    private int $lastPage;

    /**
     * @param class-string<T> $resourceName
     * @param array<string, string> $queryParams
     * @param positive-int $currentPage
     * @param positive-int $pageSize
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function __construct(
        private readonly string $resourceName,
        private readonly Client $client,
        private readonly array $queryParams,
        private int $currentPage,
        private readonly int $pageSize,
    ) {
        $this->get($this->currentPage);
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function get(int $page): void
    {
        /** @var string $endpoint */
        $endpoint = $this->resourceName::endpoint();

        /** @var array{
         *     data: array<array<string, mixed>>,
         *     total: positive-int,
         *     per_page: positive-int,
         *     current_page: positive-int,
         *     last_page: positive-int,
         * } $result
         */
        $result = $this->client->get($endpoint, array_merge($this->queryParams, [
            'page' => (string)$page,
            'size' => (string)$this->pageSize,
        ]));

        foreach ($result['data'] as $dataPerResource) {
            $this->retrievedResources[] = $this->resourceName::create($dataPerResource);
        }

        $this->currentPage = $result['current_page'];
        $this->lastPage = $result['last_page'];
    }

    /**
     * @return array<T>
     */
    public function allRetrieved(): array
    {
        return $this->retrievedResources;
    }

    /**
     * @return T
     */
    public function current(): Resource
    {
        return $this->retrievedResources[$this->currentKey];
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function next(): void
    {
        $this->currentKey++;

        if (!$this->valid() && $this->currentPage < $this->lastPage) {
            $this->get($this->currentPage + 1);
        }
    }

    public function key(): int
    {
        return $this->currentKey;
    }

    public function valid(): bool
    {
        return isset($this->retrievedResources[$this->currentKey]);
    }

    public function rewind(): void
    {
        $this->currentKey = 0;
    }
}
