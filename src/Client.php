<?php declare(strict_types=1);

namespace NatecSdk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use NatecSdk\Exceptions\NatecApiException;
use NatecSdk\Exceptions\NatecSdkException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Client
{
    protected const GET_METHOD = 'GET';
    protected const POST_METHOD = 'POST';

    private GuzzleClientInterface $client;

    public function __construct(
        private readonly string $apiToken,
        private readonly string $apiUrl = 'https://klantportaal.natec.com/api/v1',
    ) {
    }

    /**
     * @param array<string, string> $queryParams
     * @param array<string, string|array<string>> $headers
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function get(string $endpoint, array $queryParams = [], array $headers = []): ?array
    {
        try {
            $request = $this->createRequest(self::GET_METHOD, $endpoint, null, $queryParams, $headers);
            $response = $this->send($request);
            return $this->parseResponse($response);
        } catch (Throwable $exception) {
            $this->parseException($exception);
        }
    }

    /**
     * @param string $endpoint
     * @param \Psr\Http\Message\StreamInterface|resource|string $sink
     * @param array<string, string|array<string>> $headers
     * @return void
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function getPdf(string $endpoint, $sink, array $headers = []): void
    {
        try {
            $request = $this->createRequest(
                method: self::GET_METHOD,
                endpoint: $endpoint,
                headers: array_merge($headers, ['Accept' => 'application/pdf']),
            );
            $this->send($request, ['sink' => $sink]);
        } catch (Throwable $exception) {
            $this->parseException($exception);
        }
    }

    /**
     * @param array<mixed> $body
     * @param array<string, string|array<string>> $headers
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function post(string $endpoint, array $body, array $headers = []): ?array
    {
        try {
            $request = $this->createRequest(self::POST_METHOD, $endpoint, $body, [], $headers);
            $response = $this->send($request);
            return $this->parseResponse($response);
        } catch (Throwable $exception) {
            $this->parseException($exception);
        }
    }

    /**
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function parseResponse(ResponseInterface $response): ?array
    {
        try {
            Message::rewindBody($response);
            $body = $response->getBody()->getContents();
        } catch (Throwable $exception) {
            throw NatecSdkException::createFromException($exception);
        }

        if (strlen($body) < 1) {
            return null;
        }

        $decodedBody = json_decode($body, true);

        if (!is_array($decodedBody)) {
            throw new NatecSdkException('JSON decode of the response body failed. Response: ' . $body);
        }

        return $decodedBody;
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function parseException(Throwable $exception): never
    {
        if ($exception instanceof NatecSdkException) {
            throw $exception;
        } elseif ($exception instanceof BadResponseException) {
            throw NatecApiException::createForBadResponse($exception);
        } else {
            throw NatecSdkException::createFromException($exception);
        }
    }

    private function client(): GuzzleClientInterface
    {
        if (!isset($this->client)) {
            $this->client = new GuzzleClient(['timeout' => 30]);
        }

        return $this->client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array<mixed> $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->client()->send($request, array_merge([
            'http_errors' => true,
            'cookies'     => false,
        ], $options));
    }

    /**
     * @param array<mixed>|null $body
     * @param array<string, string> $queryParams
     * @param array<string, string|array<string>> $headers
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function createRequest(
        string $method,
        string $endpoint,
        ?array $body = null,
        array $queryParams = [],
        array $headers = [],
    ): Request {
        $headers = array_merge([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiToken,
        ], $headers);

        $preparedBody = null;
        if (!is_null($body)) {
            $preparedBody = json_encode($body);

            if ($preparedBody === false) {
                throw new NatecSdkException('JSON encode of the request body failed');
            }

            $headers['Content-Type'] = 'application/json';
        }

        return new Request($method, $this->formatUri($endpoint, $queryParams), $headers, $preparedBody);
    }

    /**
     * @param array<string, string> $queryParams
     */
    private function formatUri(string $endpoint, array $queryParams = []): string
    {
        $uri = sprintf('%s/%s', $this->apiUrl, $endpoint);

        /** @var string $uri */
        $uri = preg_replace('/([^:])(\/{2,})/', '$1/', $uri);

        if (!empty($queryParams)) {
            $uri .= str_contains($uri, '?') ? '&' : '?';
            $uri .= http_build_query($queryParams);
        }

        return $uri;
    }


    /**
     * Set a custom Guzzle Client, used to make the API requests.
     */
    public function setGuzzleClient(GuzzleClientInterface $client): void
    {
        $this->client = $client;
    }
}
