<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Opportunity (v1)
 *
 * Retrieves a single opportunity.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetOpportunity extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $opportunityId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/opportunities/{$this->opportunityId}";
    }
}
