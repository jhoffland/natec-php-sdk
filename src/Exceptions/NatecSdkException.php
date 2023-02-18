<?php declare(strict_types=1);

namespace NatecSdk\Exceptions;

use Exception;
use Throwable;

class NatecSdkException extends Exception
{
    public static function createFromException(Throwable $exception): self
    {
        return new NatecSdkException($exception->getMessage(), 0, $exception);
    }
}
