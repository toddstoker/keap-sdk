<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Contact
 *
 * Deletes a contact permanently.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class DeleteContact extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $contactId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}";
    }
}
