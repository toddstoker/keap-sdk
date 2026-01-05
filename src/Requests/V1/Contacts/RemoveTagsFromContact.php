<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove Tags from Contact (v1)
 *
 * Removes specific tags from a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class RemoveTagsFromContact extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  int  $contactId  The contact ID
     * @param  int|array  $tagIds  The tag IDs to remove
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly int|array $tagIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/contacts/{$this->contactId}/tags";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        $tagIds = is_array($this->tagIds) ? $this->tagIds : [$this->tagIds];

        return [
            'ids' => implode(',', $tagIds),
        ];
    }
}
