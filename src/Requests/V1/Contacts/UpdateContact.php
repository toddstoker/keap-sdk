<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Contact
 *
 * Updates an existing contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class UpdateContact extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param int $contactId The contact ID to update
     * @param array<string, mixed> $data Contact data to update
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
