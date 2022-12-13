<?php declare(strict_types=1);

namespace NatecSdk\Exceptions;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Message;
use LogicException;
use Psr\Http\Message\ResponseInterface;

class NatecApiException extends NatecSdkException
{
    /**
     * @param string $message
     * @param array<mixed> $errors
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \GuzzleHttp\Exception\BadResponseException $previous
     */
    private function __construct(
        string $message,
        public readonly array $errors,
        public readonly ResponseInterface $response,
        BadResponseException $previous
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function createForBadResponse(BadResponseException $exception): self
    {
        $response = $exception->getResponse();

        Message::rewindBody($response);
        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);

        $statusCode = $response->getStatusCode();

        $message = sprintf('Error %s: %s', $statusCode, $response->getReasonPhrase());
        $errors = [];

        if (is_array($decodedBody)) {
            if (isset($decodedBody['error']) && strlen($decodedBody['error']) > 0) {
                $message = sprintf('Error: %s (%s)', $decodedBody['error'], $statusCode);
            } elseif (isset($decodedBody['message']) && strlen($decodedBody['message']) > 0) {
                $message = sprintf('Message: %s (%s)', $decodedBody['message'], $statusCode);
            }

            if (isset($decodedBody['errors'])) {
                $errors = $decodedBody['errors'];
            }
        }

        return new self($message, $errors, $response, $exception);
    }

    public static function createFromException(Exception $exception): NatecSdkException
    {
        throw new LogicException(
            'It is not allowed to create a NatecApiException using the NatecSdkException::createFromException method.'
        );
    }
}
