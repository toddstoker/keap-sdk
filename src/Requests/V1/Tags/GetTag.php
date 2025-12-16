<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Tag (v1)
 *
 * Retrieves a single tag.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
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
