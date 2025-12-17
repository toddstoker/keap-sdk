<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\OpportunityQuery;

/**
 * List Opportunities (v1)
 *
 * Retrieves a list of all opportunities with filtering, sorting, and pagination.
 *
 * Supports offset-based pagination using limit and offset.
 * Use OpportunityQuery for building queries with filters and sorting.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListOpportunities extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  OpportunityQuery  $query  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly OpportunityQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/opportunities';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
