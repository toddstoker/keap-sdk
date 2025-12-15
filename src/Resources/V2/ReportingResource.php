<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Reporting\GetReport;
use Toddstoker\KeapSdk\Requests\V2\Reporting\ListReports;
use Toddstoker\KeapSdk\Requests\V2\Reporting\RunReport;
use Toddstoker\KeapSdk\Resources\Resource;

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
    ) {
    }

    /**
     * List reports
     *
     * Retrieves a list of Reports as defined in the application (identified as Saved Search).
     *
     * @param string|null $filter Filter to apply (e.g., "name==my-report")
     * @param string|null $orderBy Field and direction to order by (e.g., "name asc")
     * @param int|null $pageSize Total number of items to return per page
     * @param string|null $pageToken Page token for pagination
     * @return array{reports: array<array<string, mixed>>, next_page_token: ?string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(
        ?string $filter = null,
        ?string $orderBy = null,
        ?int $pageSize = null,
        ?string $pageToken = null
    ): array {
        $response = $this->connector->send(
            new ListReports($filter, $orderBy, $pageSize, $pageToken)
        );

        return $response->json();
    }

    /**
     * Get a report
     *
     * Retrieves information about a Report as defined in the application (identified as Saved Search).
     *
     * @param string $reportId The report ID
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(string $reportId): array
    {
        $response = $this->connector->send(new GetReport($reportId));

        return $response->json();
    }

    /**
     * Run a report
     *
     * Runs a report as defined in the application (identified as Saved Search).
     *
     * @param string $reportId The report ID
     * @param string|null $fields Comma-separated list of fields to return
     * @param string|null $orderBy Attribute and direction to order items by (e.g., "given_name desc")
     * @param int|null $pageSize Total number of items to return per page (max 1000, default 1000)
     * @param string|null $pageToken Page token for pagination
     * @return array{results: array<array<string, mixed>>, page_token: ?string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function run(
        string $reportId,
        ?string $fields = null,
        ?string $orderBy = null,
        ?int $pageSize = null,
        ?string $pageToken = null
    ): array {
        $response = $this->connector->send(
            new RunReport($reportId, $fields, $orderBy, $pageSize, $pageToken)
        );

        return $response->json();
    }
}
