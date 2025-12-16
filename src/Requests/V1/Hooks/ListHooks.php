<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Hooks (v1)
 *
 * Retrieves a list of all hooks.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListHooks extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/hooks';
    }
}
