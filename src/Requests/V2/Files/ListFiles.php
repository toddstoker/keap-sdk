<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\FileQuery;

class ListFiles extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly ?FileQuery $queryBuilder = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/files';
    }

    protected function defaultQuery(): array
    {
        return $this->queryBuilder?->toArray() ?? [];
    }
}
