<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Custom Field (v1)
 *
 * Creates a new custom field for contacts.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class CreateCustomField extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/contacts/model/customFields';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
