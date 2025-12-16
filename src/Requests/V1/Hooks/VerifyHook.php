<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Verify Hook (v1)
 *
 * Verifies a hook subscription.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class VerifyHook extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $key
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/hooks/{$this->key}/verify";
    }
}
