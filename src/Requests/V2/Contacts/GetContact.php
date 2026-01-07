<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\ContactFieldSelector;

/**
 * Get Contact (v2)
 *
 * Retrieves a single contact by ID.
 *
 * Supports optional field selection to specify which contact properties
 * to include in the response.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetContact extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId,
        protected readonly ?ContactFieldSelector $fieldSelector = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}";
    }

    protected function defaultQuery(): array
    {
        return $this->fieldSelector?->toArray() ?? [];
    }
}
