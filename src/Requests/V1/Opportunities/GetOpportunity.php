<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\FieldSelector\OpportunityFieldSelector;

/**
 * Get Opportunity (v1)
 *
 * Retrieves a single opportunity.
 *
 * Supports optional field selection via optional_properties parameter
 * to specify which opportunity properties to include in the response.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetOpportunity extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $opportunityId  The opportunity ID
     * @param  OpportunityFieldSelector|null  $fieldSelector  Field selector for optional properties
     */
    public function __construct(
        protected readonly int $opportunityId,
        protected readonly ?OpportunityFieldSelector $fieldSelector = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/opportunities/{$this->opportunityId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->fieldSelector?->toArray() ?? [];
    }
}
