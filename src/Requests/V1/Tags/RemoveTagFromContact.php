<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove Tag from Contact (v1)
 *
 * Removes a tag from a single contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class RemoveTagFromContact extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $tagId,
        protected readonly int $contactId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->tagId}/contacts/{$this->contactId}";
    }
}
