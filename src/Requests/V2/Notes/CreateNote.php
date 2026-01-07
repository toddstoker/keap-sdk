<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Notes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateNote extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $contactId  Contact ID
     * @param  array{
     *     user_id: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     is_pinned?: bool
     * }  $data  Note data (user_id required, title or type required)
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/notes";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
