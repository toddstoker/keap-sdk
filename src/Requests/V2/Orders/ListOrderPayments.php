<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\OrderPaymentQuery;

/**
 * List Order Payments (v2)
 *
 * Retrieves a list of payments made against a given order, including
 * historical or external payments of cash or credit card.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use OrderPaymentQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListOrderPayments extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string|int  $orderId  Order ID
     * @param  OrderPaymentQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly string|int $orderId,
        protected readonly OrderPaymentQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/orders/{$this->orderId}/payments";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
