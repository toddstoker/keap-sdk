<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\UserQuery;

/**
 * List Users (v2)
 *
 * Retrieves a list of users with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use UserQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListUsers extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  UserQuery  $query  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly UserQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/users';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
