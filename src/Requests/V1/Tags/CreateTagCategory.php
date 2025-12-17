<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Tag Category (v1)
 *
 * Creates a new tag category.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class CreateTagCategory extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/tags/categories';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
