<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Event Keys (v1)
 *
 * Retrieves a list of all available hook event keys.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListEventKeys extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/hooks/event_keys";
    }
}
