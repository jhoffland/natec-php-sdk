<?php declare(strict_types=1);

namespace NatecSdk\Exceptions;

use Exception;

class NatecSdkException extends Exception
{
    public static function createFromException(Exception $exception): self
    {
        return new NatecSdkException($exception->getMessage(), 0, $exception);
    }
}
