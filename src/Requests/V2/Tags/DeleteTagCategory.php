<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Tag Category (v2)
 *
 * Deletes a tag category.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class DeleteTagCategory extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $tagCategoryId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/tags/categories/{$this->tagCategoryId}";
    }
}
