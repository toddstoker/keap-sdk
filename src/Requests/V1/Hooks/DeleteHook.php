<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Hook (v1)
 *
 * Deletes a hook subscription.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class DeleteHook extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $key
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/hooks/{$this->key}";
    }
}
