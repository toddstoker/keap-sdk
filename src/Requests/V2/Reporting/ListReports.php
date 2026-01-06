<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Reporting;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\ReportQuery;

/**
 * List Reports (v2)
 *
 * Retrieves a list of Reports as defined in the application (identified as Saved Search).
 *
 * Supports cursor-based pagination using page_size and page_token.
 * Use ReportQuery for building complex queries with filters and sorting.
 *
 * Note: Deprecated as of v2 but still functional.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListReports extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  ReportQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly ReportQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/reporting/reports';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
