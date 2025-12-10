<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Contact (v2)
 *
 * Creates a new contact.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class CreateContact extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $data Contact data
     */
    public function __construct(
        protected readonly array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contacts';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
