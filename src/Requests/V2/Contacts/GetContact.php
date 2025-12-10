<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Contact (v2)
 *
 * Retrieves a single contact by ID.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetContact extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}";
    }
}
