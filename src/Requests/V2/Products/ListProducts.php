<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\ProductQuery;

/**
 * List Products (v2)
 *
 * Retrieves a list of products with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use ProductQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListProducts extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    /**
     * @param  ProductQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly ProductQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/products';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
