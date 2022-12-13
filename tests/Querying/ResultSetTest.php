<?php declare(strict_types=1);

namespace NatecSdk\Tests\Querying;

use GuzzleHttp\Psr7\Response;
use NatecSdk\Client;
use NatecSdk\Querying\ResultSet;
use NatecSdk\Resources\Invoice;
use NatecSdk\Tests\HttpTestCase;

class ResultSetTest extends HttpTestCase
{
    /**
     * @uses \NatecSdk\Client
     * @uses \NatecSdk\Resources\Invoice
     */
    public function test(): void
    {
        $natecClient = new Client('xxx', 'https://php-sdk.natec.com/api/v1');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new Response(200, [], file_get_contents(__DIR__ . '/_data/invoices_page1.json')), // @phpstan-ignore-line
            new Response(200, [], file_get_contents(__DIR__ . '/_data/invoices_page2.json')) // @phpstan-ignore-line
        ], $guzzleHistory));

        $invoicesPage1 = require __DIR__ . '/_data/invoices_page1.php';
        $invoicesPage2 = require __DIR__ . '/_data/invoices_page2.php';
        $invoicesAll = array_merge($invoicesPage1, $invoicesPage2);

        $invoicesIterator = new ResultSet(Invoice::class, $natecClient, [
            'postingFrom' => '2022-07-10'
        ], 1, 5);

        $this->assertEquals($invoicesPage1, $invoicesIterator->allRetrieved());

        $currentKey = 0;
        foreach ($invoicesIterator as $invoice) {
            $this->assertSame($currentKey, $invoicesIterator->key());
            $this->assertEquals($invoicesAll[$currentKey], $invoice);
            $currentKey++;
        }

        $this->assertEquals($invoicesAll, $invoicesIterator->allRetrieved());

        $this->assertEquals(2, count($guzzleHistory));
        $this->assertEquals(
            'https://php-sdk.natec.com/api/v1/invoices?page=1&size=5&postingFrom=2022-07-10',
            $guzzleHistory[0]['request']->getUri()
        );
        $this->assertEquals(
            'https://php-sdk.natec.com/api/v1/invoices?page=2&size=5&postingFrom=2022-07-10',
            $guzzleHistory[1]['request']->getUri()
        );
    }
}
