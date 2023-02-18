<?php declare(strict_types=1);

namespace NatecSdk\Exceptions;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Message;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

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
        BadResponseException $previous,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public static function createForBadResponse(BadResponseException $exception): self
    {
        $response = $exception->getResponse();

        try {
            Message::rewindBody($response);
            $body = $response->getBody()->getContents();
        } catch (Throwable $exception) {
            throw NatecSdkException::createFromException($exception);
        }

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

    /**
     * @throws \LogicException
     */
    public static function createFromException(Throwable $exception): NatecSdkException
    {
        throw new LogicException(
            'It is not allowed to create a NatecApiException using the NatecSdkException::createFromException method.',
        );
    }
}
