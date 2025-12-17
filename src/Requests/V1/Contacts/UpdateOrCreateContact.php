<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Replace Contact (v1)
 *
 * Replaces all values of a given contact.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class UpdateOrCreateContact extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/contacts';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
