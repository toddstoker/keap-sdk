<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions\ClientException;

/**
 * Exception thrown when access is forbidden (HTTP 403)
 *
 * This indicates you don't have permission to access the requested resource,
 * even though you are authenticated. Check authenticated user permissions.
 */
class ForbiddenException extends ClientException
{

}
