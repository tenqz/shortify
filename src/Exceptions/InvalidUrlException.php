<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Exceptions;

use Exception;

final class InvalidUrlException extends Exception
{
    public function __construct(string $url, int $code = 0, Exception $previous = null)
    {
        $message = sprintf('Provided URL "%s" is invalid', $url);
        parent::__construct($message, $code, $previous);
    }
}
