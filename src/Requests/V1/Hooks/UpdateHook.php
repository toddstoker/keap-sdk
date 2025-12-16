<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Hooks;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Hook (v1)
 *
 * Updates a hook subscription.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class UpdateHook extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly string $key,
        protected readonly array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/hooks/{$this->key}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
