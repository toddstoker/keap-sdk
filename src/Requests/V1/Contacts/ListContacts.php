<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\ContactQuery;

/**
 * List Contacts (v1)
 *
 * Retrieves a list of contacts with filtering, sorting, and pagination.
 *
 * Supports offset-based pagination using limit and offset.
 * Use ContactQuery for building complex queries with filters and sorting.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
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
