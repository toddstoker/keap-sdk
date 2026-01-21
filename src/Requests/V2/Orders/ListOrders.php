<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\OrderQuery;

/**
 * List Orders (v2)
 *
 * Retrieves a list of orders with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use OrderQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListOrders extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    /**
     * @param  OrderQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly OrderQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/orders';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
