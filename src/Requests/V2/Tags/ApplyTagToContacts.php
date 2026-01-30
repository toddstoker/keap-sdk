<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\HasTimeout;

/**
 * Apply Tag to Contacts (v2)
 *
 * Applies a Tag to a list of Contacts.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ApplyTagToContacts extends Request implements HasBody
{
    use HasJsonBody, HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::POST;

    /**
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  The contact IDs
     */
    public function __construct(
        protected readonly int $tagId,
        protected readonly int|array $contactIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}/contacts:applyTags";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'contact_ids' => is_array($this->contactIds)
                ? array_map('strval', $this->contactIds)
                : [strval($this->contactIds)],
        ];
    }
}
