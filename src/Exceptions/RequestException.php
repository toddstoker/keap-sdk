<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions;

use Saloon\Exceptions\Request\RequestException as SaloonRequestException;
use Saloon\Http\Response;

/**
 * Base exception for all Request-Response lifecycle Keap SDK errors
 *
 * All Keap SDK exceptions thrown during the Request lifecycle extend this class.
 */
class RequestException extends SaloonRequestException
{
    /**
     * Get the HTTP response that caused this exception
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->getSenderItem();
    }

    /**
     * Get the error message from the API response
     *
     * Attempts to extract a meaningful error message from common API response formats.
     *
     * @return string|null
     */
    public function getApiMessage(): ?string
    {
        $body = $this->getResponse()->json();

        return $body['message'] ?? $body['error_description'] ?? null;
    }
}