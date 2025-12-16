<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\TagQuery;

/**
 * List Tags (v1)
 *
 * Retrieves a list of all tags with filtering and pagination.
 *
 * Supports offset-based pagination using limit and offset.
 * Use TagQuery for building queries with filters.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListTags extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param TagQuery $query The query builder with filters and pagination
     */
    public function __construct(
        protected readonly TagQuery $queryBuilder
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
