<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Exceptions;

use Exception;

final class UrlNotFoundException extends Exception
{
    public function __construct(string $code, int $exceptionCode = 0, Exception $previous = null)
    {
        $message = sprintf('URL with code "%s" not found', $code);
        parent::__construct($message, $exceptionCode, $previous);
    }
}
