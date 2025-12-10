<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions;

/**
 * Exception thrown when a resource is not found
 */
class NotFoundException extends KeapException
{
    public function __construct(
        string $message = 'Resource not found',
        int $code = 404,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
