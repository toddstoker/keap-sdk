<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\PaymentMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\PaymentMethodQuery;

/**
 * List Payment Methods (v2)
 *
 * Retrieves a list of payment methods for a contact.
 *
 * Supports filtering by merchant_account_id, ordering by date_created,
 * and cursor-based pagination using PaymentMethodQuery.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListPaymentMethods extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int|string  $contactId  Contact ID or '-' to filter across all contacts
     * @param  PaymentMethodQuery  $query  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly int|string $contactId,
        protected readonly PaymentMethodQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}/paymentMethods";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
