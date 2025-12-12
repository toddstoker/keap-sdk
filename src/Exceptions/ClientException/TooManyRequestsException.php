<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions\ClientException;

use Saloon\Http\Response;
use Throwable;

/**
 * Exception thrown when rate limit is exceeded (HTTP 429)
 *
 * Keap enforces rate limits (typically 125 requests per second).
 * Check the retryAfter property to determine when to retry the request.
 */
class TooManyRequestsException extends ClientException
{
    /**
     * Number of seconds to wait before retrying the request
     *
     * Extracted from the Retry-After response header, if present.
     *
     * @var int|null
     */
    public readonly ?int $retryAfter;

    public function __construct(Response $response, ?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($response, $message, $code, $previous);

        // Extract retry-after header if present
        $retryAfterHeader = $response->header('Retry-After');
        $this->retryAfter = $retryAfterHeader ? (int) $retryAfterHeader : null;
    }
}
