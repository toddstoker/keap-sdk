<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\PaymentMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Payment Method (v2)
 *
 * Deletes the specified payment method.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class DeletePaymentMethod extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  int  $contactId  Contact ID
     * @param  string  $paymentMethodId  Payment method ID
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly string $paymentMethodId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}/paymentMethods/{$this->paymentMethodId}";
    }
}
