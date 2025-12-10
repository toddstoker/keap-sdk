<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions;

/**
 * Exception thrown when rate limit is exceeded
 */
class RateLimitException extends KeapException
{
    public function __construct(
        string $message = 'Rate limit exceeded',
        public readonly ?int $retryAfter = null,
        int $code = 429,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
