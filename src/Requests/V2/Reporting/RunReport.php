<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Reporting;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Run Report (v2)
 *
 * Runs a report as defined in the application (identified as Saved Search).
 *
 * Note: Deprecated as of v2 but still functional.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class RunReport extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $reportId,
        protected readonly ?string $fields = null,
        protected readonly ?string $orderBy = null,
        protected readonly ?int $pageSize = null,
        protected readonly ?string $pageToken = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/reporting/reports/{$this->reportId}:run";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->fields !== null) {
            $query['fields'] = $this->fields;
        }

        if ($this->orderBy !== null) {
            $query['order_by'] = $this->orderBy;
        }

        if ($this->pageSize !== null) {
            $query['page_size'] = $this->pageSize;
        }

        if ($this->pageToken !== null) {
            $query['page_token'] = $this->pageToken;
        }

        return $query;
    }
}
