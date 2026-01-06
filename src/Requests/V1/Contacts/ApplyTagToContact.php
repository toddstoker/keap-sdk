<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Apply Tag to Contact
 *
 * Applies a tag to a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ApplyTagToContact extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $contactId  The contact ID
     * @param  int|array<int>  $tagIds  The tag IDs to apply
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
    protected function defaultBody(): array
    {
        return [
            'tagIds' => is_array($this->tagIds) ? $this->tagIds : [$this->tagIds],
        ];
    }
}
