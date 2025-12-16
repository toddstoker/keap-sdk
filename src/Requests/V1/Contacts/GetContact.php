<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Contact
 *
 * Retrieves a single contact by ID.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetContact extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $contactId  The contact ID
     * @param  array<string>|null  $optionalProperties  Comma-delimited list of Contact properties to include in the response
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly ?array $optionalProperties = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        if ($this->optionalProperties === null) {
            return [];
        }

        return [
            'optional_properties' => implode(',', $this->optionalProperties),
        ];
    }
}
