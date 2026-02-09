<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetLeadSource extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $leadSourceId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/leadSources/{$this->leadSourceId}";
    }
}
