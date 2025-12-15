<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Tag (v2)
 *
 * Creates a new tag.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class CreateTag extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
