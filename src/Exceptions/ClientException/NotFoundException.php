<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Exceptions\ClientException;

/**
 * Exception thrown when a resource is not found (HTTP 404)
 *
 * This indicates the requested resource (contact, company, etc.) does not exist
 * or you don't have permission to access it.
 */
class NotFoundException extends ClientException
{

}
