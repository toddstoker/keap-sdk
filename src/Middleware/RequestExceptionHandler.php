<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Middleware;

use Saloon\Http\Response;
use Toddstoker\KeapSdk\Exceptions\ClientException\ClientException;
use Toddstoker\KeapSdk\Exceptions\ClientException\ForbiddenException;
use Toddstoker\KeapSdk\Exceptions\ClientException\NotFoundException;
use Toddstoker\KeapSdk\Exceptions\ClientException\TooManyRequestsException;
use Toddstoker\KeapSdk\Exceptions\ClientException\UnauthorizedException;
use Toddstoker\KeapSdk\Exceptions\RequestException;
use Toddstoker\KeapSdk\Exceptions\ServerException\InternalServerErrorException;
use Toddstoker\KeapSdk\Exceptions\ServerException\ServerException;

/**
 * Middleware to handle HTTP response errors and throw appropriate exceptions
 *
 * This middleware intercepts failed HTTP responses and converts them into
 * domain-specific exceptions.
 */
class RequestExceptionHandler
{
    public function __invoke(Response $response): void
    {
        // Don't throw on successful responses
        if (!$response->failed()) {
            return;
        }

        $previous = $response->getSenderException();
        $status = $response->status();

        $requestException = match (true) {
            // Client Errors
            $status === 401 => UnauthorizedException::class,
            $status === 403 => ForbiddenException::class,
            $status === 404 => NotFoundException::class,
            $status === 429 => TooManyRequestsException::class,

            // Server Errors
            $status === 500 => InternalServerErrorException::class,

            // Fallback to base exceptions
            $response->serverError() => ServerException::class,
            $response->clientError() => ClientException::class,
            default => RequestException::class,
        };

        throw new $requestException($response, null, 0, $previous);
    }
}