<?php declare(strict_types=1);

namespace NatecSdk\Tests\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use NatecSdk\Client as NatecClient;
use NatecSdk\Exceptions\NatecApiException;
use NatecSdk\Exceptions\NatecSdkException;
use NatecSdk\Tests\HttpTestCase;

class ClientTest extends HttpTestCase
{
    public function testValidGetRequest(): void
    {
        $data = require __DIR__ . '/_data/valid_get_request.php';

        $natecClient = new NatecClient($data['apiToken'], $data['apiUrl']);

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([$data['response']], $guzzleHistory));

        $result = $natecClient->get(...$data['input']);
        $this->assertEquals($data['output'], $result);

        $this->evaluateGuzzleHistory($guzzleHistory, [$data['expectedRequest']], [$data['response']], []);
    }

    public function testValidPostRequest(): void
    {
        $data = require __DIR__ . '/_data/valid_post_request.php';

        $natecClient = new NatecClient($data['apiToken'], $data['apiUrl']);

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([$data['response']], $guzzleHistory));

        $result = $natecClient->post(...$data['input']);
        $this->assertEquals($data['output'], $result);

        $this->evaluateGuzzleHistory($guzzleHistory, [$data['expectedRequest']], [$data['response']], []);
    }

    /**
     * @uses \NatecSdk\Exceptions\NatecApiException
     */
    public function testApiExceptionParsing(): void
    {
        $natecClient = new NatecClient('xxx', 'https://php-sdk.natec.com/api/v1/');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new Response(
                400,
                [],
                json_encode(['message' => 'Invalid data'], JSON_THROW_ON_ERROR)
            )
        ], $guzzleHistory));

        $this->expectException(NatecApiException::class);
        $natecClient->post('/test-invalid-post', ['key' => 'value']);
    }

    /**
     * @uses \NatecSdk\Exceptions\NatecSdkException
     */
    public function testSdkExceptionParsing(): void
    {
        $natecClient = new NatecClient('xxx', 'https://php-sdk.natec.com/api/v1/');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new ConnectException(
                'A Connection Exception',
                new Request('GET', 'https://php-sdk.natec.com/api/v1/something-wrong', [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer xxx',
                    'User-Agent' => 'NatecSdk/Test'
                ])
            )
        ], $guzzleHistory));

        $this->expectException(NatecSdkException::class);
        $natecClient->get('/something-wrong', [], ['User-Agent' => 'NatecSdk/Test']);
    }

    public function testEmptyResponse(): void
    {
        $natecClient = new NatecClient('xxx', 'https://php-sdk.natec.com/api/v1/');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([new Response(200)], $guzzleHistory));

        $result = $natecClient->get('/empty');

        $this->assertSame(null, $result);
    }

    /**
     * @uses \NatecSdk\Exceptions\NatecSdkException
     */
    public function testInvalidJsonResponse(): void
    {
        $natecClient = new NatecClient('xxx', 'https://php-sdk.natec.com/api/v1/');

        $guzzleHistory = [];
        $natecClient->setGuzzleClient($this->createGuzzleClient([
            new Response(
                200,
                [],
                '{"key": "value"]'
            )
        ], $guzzleHistory));

        $this->expectException(NatecSdkException::class);
        $this->expectExceptionMessage('JSON decode of the response body failed. Response: {"key": "value"]');

        $natecClient->get('/test-invalid-json');
    }
}
