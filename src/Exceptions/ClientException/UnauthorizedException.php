<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions\ClientException;

/**
 * Exception thrown when authentication fails (HTTP 401)
 *
 * This typically indicates invalid or expired credentials.
 * Check your access token, refresh token, or API credentials.
 */
class UnauthorizedException extends ClientException
{

}
