<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Reporting;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Report (v2)
 *
 * Retrieves information about a Report as defined in the application (identified as Saved Search).
 *
 * Note: Deprecated as of v2 but still functional.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetReport extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $reportId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/reporting/reports/{$this->reportId}";
    }
}
