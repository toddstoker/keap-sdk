<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Webforms;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\WebformQuery;

/**
 * List Webforms (v2)
 *
 * Retrieves a list of webforms with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_size and page_token.
 * Use WebformQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListWebforms extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  WebformQuery  $queryBuilder  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly WebformQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/webforms';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
