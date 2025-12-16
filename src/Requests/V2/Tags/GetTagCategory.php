<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Tag Category (v2)
 *
 * Retrieves a single tag category by ID.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetTagCategory extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $tagCategoryId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/tags/categories/{$this->tagCategoryId}";
    }
}
