<?php declare(strict_types=1);

namespace NatecSdk\Querying;

use Iterator;
use NatecSdk\Client;
use NatecSdk\Resources\Resource;

/**
 * @implements \Iterator<integer, Resource>
 */
class ResultSet implements Iterator
{
    /** @var array<Resource> */
    private array $retrievedResources = [];

    private int $currentKey = 0;

    private int $lastPage;

    /**
     * @param class-string<Resource> $resourceName
     * @param \NatecSdk\Client $client
     * @param array<string, string> $queryParams
     * @param int $currentPage
     * @param int $pageSize
     */
    public function __construct(
        private readonly string $resourceName,
        private readonly Client $client,
        private readonly array $queryParams,
        private int $currentPage,
        private readonly int $pageSize
    ) {
        $this->get($this->currentPage);
    }

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
         *     first_page_url?: string,
         *     last_page_url?: string,
         *     next_page_url?: string,
         *     prev_page_url?: ?string,
         *     path?: string,
         *     from?: positive-int,
         *     to?: positive-int,
         *     links?: array<array{ url: ?string, label: string, active: bool }>
         * } $result
         */
        $result = $this->client->get($endpoint, array_merge([
            'page' => (string)$page,
            'size' => (string)$this->pageSize
        ], $this->queryParams));

        foreach ($result['data'] as $dataPerResource) {
            $this->retrievedResources[] = new $this->resourceName($dataPerResource);
        }

        $this->currentPage = $result['current_page'];
        $this->lastPage = $result['last_page'];
    }

    /**
     * @return array<Resource>
     */
    public function allRetrieved(): array
    {
        return $this->retrievedResources;
    }

    public function current(): Resource
    {
        return $this->retrievedResources[$this->currentKey];
    }

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
