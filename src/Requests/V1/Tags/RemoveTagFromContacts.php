<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove Tag from Contacts (v1)
 *
 * Removes a tag from a list of contacts.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class RemoveTagFromContacts extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $tagId,
        protected readonly array $contactIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/tags/{$this->tagId}/contacts";
    }

    protected function defaultQuery(): array
    {
        return [
            'ids' => implode(',', $this->contactIds),
        ];
    }
}
