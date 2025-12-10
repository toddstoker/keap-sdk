<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions;

/**
 * Exception thrown when request validation fails
 */
class ValidationException extends KeapException
{
    /**
     * @param array<string, mixed> $errors
     */
    public function __construct(
        string $message = 'Validation failed',
        public readonly array $errors = [],
        int $code = 422,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
