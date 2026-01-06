<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\TagCompanyQuery;

/**
 * List Companies with Tag (v2)
 *
 * Retrieves a list of companies that have a specific tag with filtering,
 * sorting, and pagination.
 *
 * Supports cursor-based pagination using page_size and page_token.
 * Use TagCompanyQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListCompaniesWithTag extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $tagId  The tag ID
     * @param  TagCompanyQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly int $tagId,
        protected readonly TagCompanyQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}/companies";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
