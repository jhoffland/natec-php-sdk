<?php declare(strict_types=1);

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

$requestBody = [
    'item'     => '1234',
    'quantity' => 25,
];
$requestBodyEncoded = json_encode($requestBody, JSON_THROW_ON_ERROR);

$responseBody = ['state' => 1];

return [
    'apiToken'        => 'xxx-test-xxx',
    'apiUrl'          => 'https://php-sdk.natec.com/api/v1/',
    'input'           => [
        'endpoint' => '/test-post-request',
        'body'     => $requestBody,
        'headers'  => ['User-Agent' => 'NatecSdk/Test'],
    ],
    'response'        => new Response(201, [], json_encode($responseBody, JSON_THROW_ON_ERROR)),
    'output'          => $responseBody,
    'expectedRequest' => new Request('POST', 'https://php-sdk.natec.com/api/v1/test-post-request', [
        'Accept'         => 'application/json',
        'Authorization'  => 'Bearer xxx-test-xxx',
        'Content-Type'   => 'application/json',
        'User-Agent'     => 'NatecSdk/Test',
        'Content-Length' => (string)strlen($requestBodyEncoded),
    ], $requestBodyEncoded),
];
