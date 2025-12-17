<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Contacts with Tag (v1)
 *
 * Retrieves a list of contacts that have the given tag applied.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListContactsWithTag extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $tagId,
        protected readonly ?int $limit = null,
        protected readonly ?int $offset = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/tags/{$this->tagId}/contacts";
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
