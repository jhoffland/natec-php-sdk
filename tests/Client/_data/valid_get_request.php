<?php declare(strict_types=1);

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

$data = [
    'count' => 25,
    'type' => 'valid-request'
];

return [
    'apiToken' => 'xxx-test-xxx',
    'apiUrl' => 'https://php-sdk.natec.com/api/v1/',
    'input' => [
        'endpoint' => '/test-get-request?method=get',
        'queryParams' => [
            'type' => 'test'
        ],
        'headers' => [
            'x-type' => 'test',
            'User-Agent' => 'NatecSdk/Test'
        ]
    ],
    'response' => new Response(200, [], json_encode($data, JSON_THROW_ON_ERROR)),
    'output' => $data,
    'expectedRequest' =>
        new Request('GET', 'https://php-sdk.natec.com/api/v1/test-get-request?method=get&type=test', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer xxx-test-xxx',
            'x-type' => 'test',
            'User-Agent' => 'NatecSdk/Test'
        ], null)
];
