<?php declare(strict_types=1);

namespace NatecSdk\Tests\Querying;

use GuzzleHttp\Psr7\Response;
use NatecSdk\Client;
use NatecSdk\Resources\Invoice;

class FindableTest extends \NatecSdk\Tests\HttpTestCase
{
    /**
     * @uses \NatecSdk\Resources\Invoice
     * @uses \NatecSdk\Client
     */
    public function test() : void
    {
        $natecClient = new Client('xxx', 'https://php-sdk.natec.com/api/v1');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new Response(200, [], file_get_contents(__DIR__ . '/_data/invoice_single.json')), // @phpstan-ignore-line
        ], $guzzleHistory));

        $expectedInvoice = require __DIR__ . '/_data/invoice_single.php';
        $actualInvoice = Invoice::find($natecClient, 'GVFN22-11122');

        $this->assertEquals($expectedInvoice, $actualInvoice);

        $this->assertEquals(1, count($guzzleHistory));
        $this->assertEquals(
            'https://php-sdk.natec.com/api/v1/invoices/GVFN22-11122',
            $guzzleHistory[0]['request']->getUri()
        );
    }
}
