<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Lead Score (v2)
 *
 * Retrieves information about the Lead Score of a Contact.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetLeadScore extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/leadScore";
    }
}
