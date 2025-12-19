<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\FieldSelector\ContactFieldSelector;

/**
 * Get Contact (v1)
 *
 * Retrieves a single contact by ID.
 *
 * Supports optional field selection via optional_properties parameter
 * to specify which contact properties to include in the response.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetContact extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $contactId  The contact ID
     * @param  ContactFieldSelector|null  $fieldSelector  Field selector for optional properties
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly ?ContactFieldSelector $fieldSelector = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/contacts/{$this->contactId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->fieldSelector?->toArray() ?? [];
    }
}
