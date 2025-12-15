<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Tag (v2)
 *
 * Updates an existing tag.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class UpdateTag extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected readonly int $tagId,
        protected readonly array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tags/{$this->tagId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
