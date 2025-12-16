<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Companies with Tag (v1)
 *
 * Retrieves a list of companies that have the given tag applied.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListCompaniesWithTag extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $tagId,
        protected readonly ?int $limit = null,
        protected readonly ?int $offset = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->tagId}/companies";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->limit !== null) {
            $query['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $query['offset'] = $this->offset;
        }

        return $query;
    }
}
