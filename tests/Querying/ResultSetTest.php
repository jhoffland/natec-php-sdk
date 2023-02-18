<?php declare(strict_types=1);

namespace NatecSdk\Tests\Querying;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use NatecSdk\Client;
use NatecSdk\Querying\ResultSet;
use NatecSdk\Resources\Invoice;
use NatecSdk\Tests\HttpTestCase;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Client::class)]
#[UsesClass(Invoice::class)]
class ResultSetTest extends HttpTestCase
{
    public function test(): void
    {
        $natecClient = new Client('xxx', 'https://php-sdk.natec.com/api/v1');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new Response(200, [], file_get_contents(__DIR__ . '/_data/invoices_page1.json')), // @phpstan-ignore-line
            new Response(200, [], file_get_contents(__DIR__ . '/_data/invoices_page2.json')), // @phpstan-ignore-line
        ], $guzzleHistory));

        $invoicesPage1 = require __DIR__ . '/_data/invoices_page1.php';
        $invoicesPage2 = require __DIR__ . '/_data/invoices_page2.php';
        $invoicesAll = array_merge($invoicesPage1, $invoicesPage2);

        $invoicesIterator = new ResultSet(Invoice::class, $natecClient, ['postingFrom' => '2022-07-10'], 1, 5);

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
            new Uri('https://php-sdk.natec.com/api/v1/invoices?postingFrom=2022-07-10&page=1&size=5'),
            $guzzleHistory[0]['request']->getUri(),
        );
        $this->assertEquals(
            new Uri('https://php-sdk.natec.com/api/v1/invoices?postingFrom=2022-07-10&page=2&size=5'),
            $guzzleHistory[1]['request']->getUri(),
        );
    }
}
