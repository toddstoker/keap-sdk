<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Opportunities;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\OpportunityStageQuery;

/**
 * List Opportunity Stages (v2)
 *
 * Retrieves a list of Opportunity Stages.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use OpportunityStageQuery for building queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListOpportunityStages extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    /**
     * @param  OpportunityStageQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly OpportunityStageQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/opportunities/stages';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
