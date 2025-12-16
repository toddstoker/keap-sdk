<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Tag Category (v2)
 *
 * Updates an existing tag category.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class UpdateTagCategory extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected readonly int $tagCategoryId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/tags/categories/{$this->tagCategoryId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
