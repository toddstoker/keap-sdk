<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Opportunity (v1)
 *
 * Updates an existing opportunity.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class UpdateOpportunity extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected readonly int $opportunityId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/opportunities/{$this->opportunityId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
