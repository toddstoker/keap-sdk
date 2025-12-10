<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Contact (v2)
 *
 * Updates an existing contact.
 *
 * @see https://developer.keap.com/docs/restv2/
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
