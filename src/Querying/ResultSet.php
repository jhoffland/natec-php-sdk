<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use Iterator;
use NatecSdk\Client;
use NatecSdk\Resources\AbstractResource;

/**
 * @template T of \NatecSdk\Resources\AbstractResource
 *
 * @implements \Iterator<integer, T>
 */
class ResultSet implements Iterator
{
    /** @var array<T> */
    private array $retrievedResources = [];

    private int $currentKey = 0;

    private int $currentPage;
    private int $lastPage;

    /**
     * @param class-string<T> $resourceClassName
     * @param array<string, string> $queryParams
     * @param positive-int $startPage
     * @param positive-int $pageSize
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function __construct(
        private readonly string $resourceClassName,
        private readonly Client $client,
        private readonly array $queryParams,
        private readonly int $startPage,
        private readonly int $pageSize,
    ) {
        $this->get($this->startPage);
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function get(int $page): void
    {
        /** @var string $endpoint */
        $endpoint = $this->resourceClassName::endpoint();

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
            $this->retrievedResources[] = $this->resourceClassName::create($dataPerResource);
        }

        $this->currentPage = $result['current_page'];
        $this->lastPage = $result['last_page'];
    }

    /**
     * @return T
     */
    public function current(): AbstractResource
    {
        return $this->retrievedResources[$this->currentKey];
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function next(): void
    {
        unset($this->retrievedResources[$this->currentKey]);

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

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function rewind(): void
    {
        $this->currentKey = 0;
        $this->retrievedResources = [];
        $this->get($this->startPage);
    }
}
