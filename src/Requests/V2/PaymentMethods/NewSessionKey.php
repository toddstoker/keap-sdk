<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\PaymentMethods;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Retrieve New Session Key (v2)
 *
 * Creates a new session key to be used in creating payment methods.
 *
 * @see https://developer.infusionsoft.com/payments-api-integration-configuration/
 */
class NewSessionKey extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $contactId  Contact ID
     */
    public function __construct(
        protected readonly int $contactId
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/paymentMethodConfigs/';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'contact_id' => $this->contactId,
        ];
    }
}
