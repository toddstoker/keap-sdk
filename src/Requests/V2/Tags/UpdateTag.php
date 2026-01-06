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

    /**
     * @param  int  $tagId  The tag ID
     * @param  array<string, mixed>  $data  Tag data
     */
    public function __construct(
        protected readonly int $tagId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/tags/{$this->tagId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
