<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove Tag from Contact
 *
 * Removes a tag from a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class RemoveTagFromContact extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  int  $contactId  The contact ID
     * @param  int  $tagId  The tag ID to remove
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly int $tagId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/tags/{$this->tagId}";
    }
}
