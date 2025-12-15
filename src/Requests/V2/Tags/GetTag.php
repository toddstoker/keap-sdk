<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Tag (v2)
 *
 * Retrieves a single tag by ID.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetTag extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $tagId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->tagId}";
    }
}
