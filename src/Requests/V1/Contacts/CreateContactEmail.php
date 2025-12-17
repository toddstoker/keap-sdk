<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Contact Email (v1)
 *
 * Creates a record of an email sent to a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class CreateContactEmail extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $contactId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/contacts/{$this->contactId}/emails";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
