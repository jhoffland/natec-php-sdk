<?php declare(strict_types=1);

namespace NatecSdk\Tests\Querying;

use GuzzleHttp\Psr7\Response;
use NatecSdk\Client;
use NatecSdk\Querying\ResultSet;
use NatecSdk\Resources\Invoice;
use NatecSdk\Tests\HttpTestCase;

class QueryableTest extends HttpTestCase
{
    /**
     * @uses \NatecSdk\Client
     * @uses \NatecSdk\Resources\Invoice
     * @uses \NatecSdk\Querying\ResultSet
     */
    public function test(): void
    {
        $natecClient = new Client('xxx', 'https://php-sdk.natec.com/api/v1');

        $guzzleHistory = [];

        $response = new Response(
            200,
            [],
            file_get_contents(__DIR__ . '/_data/invoices_page1.json') // @phpstan-ignore-line
        );

        $natecClient->setGuzzleClient($this->createGuzzleClient([$response, $response], $guzzleHistory));

        $expected = new ResultSet(Invoice::class, $natecClient, [], 1, 5);
        $actual = Invoice::get($natecClient, [], 1, 5);

        $this->assertEquals($expected, $actual);
    }
}
