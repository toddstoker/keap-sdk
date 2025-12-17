<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Contact Links (v2)
 *
 * Retrieves a list of Linked Contacts for a given Contact.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListContactLinks extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}/links";
    }
}
