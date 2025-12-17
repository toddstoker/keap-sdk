<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Hook (v1)
 *
 * Retrieves a single hook subscription.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetHook extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $key
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/hooks/{$this->key}";
    }
}
