<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Reports;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\RunReportQuery;

/**
 * Run Report (v2)
 *
 * Runs a report as defined in the application (identified as Saved Search).
 *
 * Supports cursor-based pagination using page_size and page_token.
 * Use RunReportQuery for building queries with field selection, sorting, and pagination.
 *
 * Note: Deprecated as of v2 but still functional.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class RunReport extends Request
{
    protected Method $method = Method::POST;

    /**
     * @param  string  $reportId  The report ID to run
     * @param  RunReportQuery  $queryBuilder  The query builder with fields, sorting, and pagination
     */
    public function __construct(
        protected readonly string $reportId,
        protected readonly RunReportQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/reporting/reports/{$this->reportId}:run";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
