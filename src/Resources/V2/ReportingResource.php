<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Reporting\GetReport;
use Toddstoker\KeapSdk\Requests\V2\Reporting\ListReports;
use Toddstoker\KeapSdk\Requests\V2\Reporting\RunReport;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\ReportQuery;
use Toddstoker\KeapSdk\Support\V2\RunReportQuery;

/**
 * Reporting Resource (v2)
 *
 * Provides methods for accessing and running reports (Saved Searches).
 *
 * Note: These endpoints are deprecated as of v2 but still functional.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class ReportingResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List reports with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  ReportQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{reports: array<array<string, mixed>>, next_page_token: ?string}
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?ReportQuery $query = null): array
    {
        $query = $query ?? ReportQuery::make();

        $response = $this->connector->send(new ListReports($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list reports endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  ReportQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?ReportQuery $query = null): Paginator
    {
        $query = $query ?? ReportQuery::make();

        return new Paginator(
            fn (ReportQuery $q) => $this->list($q),
            $query,
            'reports'
        );
    }

    /**
     * Get a report
     *
     * Retrieves information about a Report as defined in the application (identified as Saved Search).
     *
     * @param  string  $reportId  The report ID
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(string $reportId): array
    {
        $response = $this->connector->send(new GetReport($reportId));

        return $response->json();
    }

    /**
     * Run a report with field selection, sorting, and pagination
     *
     * Returns a single page of results. Use newRunPaginator() to automatically
     * iterate through all pages.
     *
     * @param  string  $reportId  The report ID
     * @param  RunReportQuery|null  $query  Query builder with field selection, sorting, and pagination
     * @return array{results: array<array<string, mixed>>, page_token: ?string}
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function run(string $reportId, ?RunReportQuery $query = null): array
    {
        $query = $query ?? RunReportQuery::make();

        $response = $this->connector->send(new RunReport($reportId, $query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through report results.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  string  $reportId  The report ID
     * @param  RunReportQuery|null  $query  Query builder with field selection, sorting, and pagination
     */
    public function newRunPaginator(string $reportId, ?RunReportQuery $query = null): Paginator
    {
        $query = $query ?? RunReportQuery::make();

        return new Paginator(
            fn (RunReportQuery $q) => $this->run($reportId, $q),
            $query,
            'results'
        );
    }
}
