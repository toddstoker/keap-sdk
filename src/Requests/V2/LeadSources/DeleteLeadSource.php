<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteLeadSource extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $leadSourceId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/leadSources/{$this->leadSourceId}";
    }
}
