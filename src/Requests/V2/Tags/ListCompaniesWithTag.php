<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Companies with Tag (v2)
 *
 * Retrieves a list of companies that have a specific tag.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListCompaniesWithTag extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $tagId,
        protected readonly ?int $pageSize = null,
        protected readonly ?string $pageToken = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}/companies";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->pageSize !== null) {
            $query['page_size'] = $this->pageSize;
        }

        if ($this->pageToken !== null) {
            $query['page_token'] = $this->pageToken;
        }

        return $query;
    }
}
