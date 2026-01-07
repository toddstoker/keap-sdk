<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\LeadSourceQuery;

class ListLeadSources extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly LeadSourceQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/leadSources';
    }

    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
