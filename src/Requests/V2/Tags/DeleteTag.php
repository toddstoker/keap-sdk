<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Tag (v2)
 *
 * Deletes a tag.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class DeleteTag extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $tagId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}";
    }
}
