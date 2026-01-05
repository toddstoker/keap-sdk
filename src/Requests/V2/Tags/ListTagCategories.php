<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\TagCategoryQuery;

/**
 * List Tag Categories (v2)
 *
 * Retrieves a list of tag categories with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_size and page_token.
 * Use TagCategoryQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListTagCategories extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  TagCategoryQuery  $query  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly TagCategoryQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/tags/categories';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
