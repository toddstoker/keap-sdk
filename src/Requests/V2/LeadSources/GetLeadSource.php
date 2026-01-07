<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetLeadSource extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $leadSourceId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/leadSources/{$this->leadSourceId}";
    }
}
