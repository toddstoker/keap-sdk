<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Credit Cards (v1)
 *
 * Retrieves all credit cards for a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetCreditCards extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/creditCards";
    }
}
