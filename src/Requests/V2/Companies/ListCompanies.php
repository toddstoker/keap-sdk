<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\CompanyQuery;

/**
 * List Companies (v2)
 *
 * Retrieves a list of companies with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use CompanyQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListCompanies extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    /**
     * @param  CompanyQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly CompanyQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/companies';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
