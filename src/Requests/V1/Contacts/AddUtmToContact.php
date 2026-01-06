<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add UTM to Contact (v1)
 *
 * Adds UTM parameters to a contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class AddUtmToContact extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $contactId  The contact ID
     * @param  array<string, mixed>  $data  UTM data
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/contacts/{$this->contactId}/utm";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
