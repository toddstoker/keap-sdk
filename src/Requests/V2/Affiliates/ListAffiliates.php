<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Affiliates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\AffiliateQuery;

class ListAffiliates extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly AffiliateQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/affiliates';
    }

    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
