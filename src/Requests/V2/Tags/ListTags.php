<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Tags (v2)
 *
 * Retrieves a list of tags with filtering and pagination.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListTags extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly ?string $filter = null,
        protected readonly ?string $orderBy = null,
        protected readonly ?int $pageSize = null,
        protected readonly ?string $pageToken = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->filter !== null) {
            $query['filter'] = $this->filter;
        }

        if ($this->orderBy !== null) {
            $query['order_by'] = $this->orderBy;
        }

        if ($this->pageSize !== null) {
            $query['page_size'] = $this->pageSize;
        }

        if ($this->pageToken !== null) {
            $query['page_token'] = $this->pageToken;
        }

        return $query;
    }
}
