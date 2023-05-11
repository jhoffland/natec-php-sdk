<?php declare(strict_types=1);

namespace NatecSdk\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

abstract class HttpTestCase extends TestCase
{
    /**
     * @param array<(\Psr\Http\Message\ResponseInterface|\Throwable)> $mockQueue
     * @param array<empty> $guzzleHistory
     */
    protected function createGuzzleClient(array $mockQueue, array &$guzzleHistory): GuzzleClient
    {
        $handlerStack = HandlerStack::create(new MockHandler($mockQueue));
        $handlerStack->push(Middleware::history($guzzleHistory));

        return new GuzzleClient([
            'http_errors' => true,
            'handler'     => $handlerStack,
        ]);
    }

    /**
     * @param array<array{
     *     request: \Psr\Http\Message\RequestInterface,
     *     response?: \Psr\Http\Message\ResponseInterface,
     *     error?: \Exception
     * }> $history
     * @param array<\Psr\Http\Message\RequestInterface> $expectedRequests
     * @param array<\Psr\Http\Message\ResponseInterface> $expectedResponses
     * @param array<\Exception> $expectedErrors
     */
    protected function evaluateGuzzleHistory(
        array $history,
        array $expectedRequests,
        array $expectedResponses,
        array $expectedErrors,
    ): void {
        $this->assertSameSize($expectedRequests, $history);
        $this->assertCount(count($expectedResponses) + count($expectedErrors), $history);

        $requestCount = 0;
        $responseCount = 0;
        $errorsCount = 0;

        foreach ($history as $transaction) {
            $this->evaluateRequest($expectedRequests[$requestCount], $transaction['request']);
            $requestCount++;

            if (isset($transaction['response'])) {
                $this->assertEquals($expectedResponses[$responseCount], $transaction['response']);
                $responseCount++;
            } elseif (isset($transaction['error'])) {
                $this->assertEquals($expectedErrors[$errorsCount], $transaction['error']);
                $errorsCount++;
            }
        }
    }

    protected function evaluateRequest(RequestInterface $expected, RequestInterface $actual): void
    {
        $this->assertEquals($expected->getMethod(), $actual->getMethod());
        $this->assertEquals($expected->getUri(), $actual->getUri());
        $this->assertEquals($expected->getHeaders(), $actual->getHeaders());
        $this->assertEquals($expected->getBody()->getContents(), $actual->getBody()->getContents());
        $this->assertEquals($expected->getProtocolVersion(), $actual->getProtocolVersion());
    }
}
