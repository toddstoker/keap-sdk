<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Tag Categories (v2)
 *
 * Retrieves a list of tag categories.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class ListTagCategories extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/tags/categories";
    }
}
