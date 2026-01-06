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

    /**
     * @param  string  $key  Hook key
     * @param  array<string, mixed>  $data  Hook subscription data
     */
    public function __construct(
        protected readonly string $key,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/hooks/{$this->key}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
