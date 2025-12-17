<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Opportunity (v1)
 *
 * Creates a new opportunity.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class CreateOpportunity extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/opportunities';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
