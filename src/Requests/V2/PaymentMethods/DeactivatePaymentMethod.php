<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\PaymentMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deactivate Payment Method (v2)
 *
 * Deactivates the specified payment method.
 *
 * This endpoint uses a custom action suffix (:deactivate) to perform
 * the deactivation operation via POST request.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class DeactivatePaymentMethod extends Request
{
    protected Method $method = Method::POST;

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
        return "/v2/contacts/{$this->contactId}/paymentMethods/{$this->paymentMethodId}:deactivate";
    }
}
