<?php declare(strict_types=1);

namespace NatecSdk;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use NatecSdk\Exceptions\NatecApiException;
use NatecSdk\Exceptions\NatecSdkException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private GuzzleClient $client;

    public function __construct(
        private readonly string $apiToken,
        private readonly string $apiUrl = 'https://klantportaal.natec.com/api/v1'
    ) {
    }

    /**
     * @param string $endpoint
     * @param array<string, string> $queryParams
     * @param array<string, string|array<string>> $headers
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function get(string $endpoint, array $queryParams = [], array $headers = []): ?array
    {
        try {
            $request = $this->createRequest('GET', $endpoint, null, $queryParams, $headers);
            $response = $this->client()->send($request);
            return $this->parseResponse($response);
        } catch (Exception $exception) {
            $this->parseException($exception);
        }
    }

    /**
     * @param string $endpoint
     * @param array<mixed> $body
     * @param array<string, string|array<string>> $headers
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function post(string $endpoint, array $body, array $headers = []): ?array
    {
        try {
            $request = $this->createRequest('POST', $endpoint, $body, [], $headers);
            $response = $this->client()->send($request);
            return $this->parseResponse($response);
        } catch (Exception $exception) {
            $this->parseException($exception);
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array<mixed>|null
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function parseResponse(ResponseInterface $response): ?array
    {
        Message::rewindBody($response);
        $body = $response->getBody()->getContents();

        if (strlen($body) < 1) {
            return null;
        }

        $decodedBody = json_decode($body, true);

        if (!is_array($decodedBody)) {
            throw new NatecSdkException(sprintf('JSON decode of the response body failed. Response: ' . $body));
        }

        return $decodedBody;
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function parseException(Exception $exception): void
    {
        if ($exception instanceof NatecSdkException) {
            throw $exception;
        } elseif ($exception instanceof BadResponseException) {
            throw NatecApiException::createForBadResponse($exception);
        } else {
            throw NatecSdkException::createFromException($exception);
        }
    }

    private function client(): GuzzleClient
    {
        if (!isset($this->client)) {
            $this->client = new GuzzleClient([ // @codeCoverageIgnore
                'http_errors' => true
            ]);
        }

        return $this->client;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array<mixed>|null $body
     * @param array<string, string> $queryParams
     * @param array<string, string|array<string>> $headers
     * @return \GuzzleHttp\Psr7\Request
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    private function createRequest(
        string $method,
        string $endpoint,
        ?array $body = null,
        array $queryParams = [],
        array $headers = []
    ): Request {
        $headers = array_merge($headers, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiToken,
        ]);

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
     * @param string $endpoint
     * @param array<string, string> $queryParams
     * @return string
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
     *
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function setGuzzleClient(GuzzleClient $client): void
    {
        $this->client = $client;
    }
}
