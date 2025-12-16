<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove All Tags from Contact (v1)
 *
 * Removes all tags from a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class RemoveAllTagsFromContact extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $contactId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/tags";
    }
}
