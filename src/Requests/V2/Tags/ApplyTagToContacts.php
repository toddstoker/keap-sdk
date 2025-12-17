<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Apply Tag to Contacts (v2)
 *
 * Applies a Tag to a list of Contacts.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ApplyTagToContacts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $tagId,
        protected readonly array $contactIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}/contacts:applyTags";
    }

    protected function defaultBody(): array
    {
        return [
            'contact_ids' => array_map('strval', $this->contactIds),
        ];
    }
}
