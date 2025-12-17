<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Remove Tag from Contacts (v2)
 *
 * Removes a Tag from a list of Contacts.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class RemoveTagFromContacts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $tagId,
        protected readonly array $contactIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}/contacts:removeTags";
    }

    protected function defaultBody(): array
    {
        return [
            'contact_ids' => array_map('strval', $this->contactIds),
        ];
    }
}
