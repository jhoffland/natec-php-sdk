<?php declare(strict_types=1);

namespace NatecSdk\Tests\Exceptions;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LogicException;
use NatecSdk\Exceptions\NatecApiException;
use PHPUnit\Framework\TestCase;

class NatecApiExceptionTest extends TestCase
{
    /**
     * @return array<string, array{0: \GuzzleHttp\Exception\BadResponseException, 1: string, 2: array<mixed>}>
     */
    public function dataProvider(): array
    {
        $request = new Request('GET', '/natec-sdk/test');

        return [
            'Test ClientException with error' => [
                new ClientException('Client exception', $request, new Response(404, [], json_encode([
                    'error' => 'Test Error'
                ], JSON_THROW_ON_ERROR))),
                'Error: Test Error (404)',
                []
            ],
            'Test ClientException with message and errors' => [
                new ClientException('Client exception', $request, new Response(400, [], json_encode([
                    'message' => 'Invalid data',
                    'errors' => ['field' => 'invalid input for field']
                ], JSON_THROW_ON_ERROR))),
                'Message: Invalid data (400)',
                ['field' => 'invalid input for field']
            ],
            'Test ServerException' => [
                new ServerException('Server exception', $request, new Response(500, [])),
                'Error 500: Internal Server Error',
                []
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param \GuzzleHttp\Exception\BadResponseException $previousException
     * @param string $message
     * @param array<mixed> $errors
     * @return void
     */
    public function testCreateForBadResponse(
        BadResponseException $previousException,
        string $message,
        array $errors
    ): void {
        $exception = NatecApiException::createForBadResponse($previousException);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($errors, $exception->errors);
        $this->assertSame($previousException->getResponse(), $exception->response);
        $this->assertSame($previousException, $exception->getPrevious());
    }

    public function testCreateFromException(): void
    {
        $this->expectException(LogicException::class);

        NatecApiException::createFromException(new Exception('Test'));
    }
}
