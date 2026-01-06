<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Apply Tag to Contacts (v1)
 *
 * Applies a tag to a list of contacts.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ApplyTagToContacts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  The contact IDs
     */
    public function __construct(
        protected readonly int $tagId,
        protected readonly array $contactIds
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/tags/{$this->tagId}/contacts";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'ids' => $this->contactIds,
        ];
    }
}
