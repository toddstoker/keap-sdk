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
     * @param int $contactId The contact ID
     * @param int $tagId The tag ID to apply
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly int $tagId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/tags";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'tagIds' => [$this->tagId],
        ];
    }
}
