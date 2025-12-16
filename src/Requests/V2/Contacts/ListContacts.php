<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\ContactQuery;

/**
 * List Contacts (v2)
 *
 * Retrieves a list of contacts with filtering, sorting, and pagination.
 *
 * Supports cursor-based pagination using page_token and page_size.
 * Use ContactQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListContacts extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  ContactQuery  $query  The query builder with filters, sorting, and pagination
     */
    public function __construct(
        protected readonly ContactQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/contacts';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
