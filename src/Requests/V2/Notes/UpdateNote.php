<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Notes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateNote extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  int  $contactId  Contact ID
     * @param  int  $noteId  Note ID
     * @param  array{
     *     user_id: string,
     *     contact_id?: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     is_pinned?: bool
     * }  $data  Note data to update (user_id required)
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     */
    public function __construct(
        protected readonly int $contactId,
        protected readonly int $noteId,
        protected readonly array $data,
        protected readonly ?array $updateMask = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/notes/{$this->noteId}";
    }

    protected function defaultQuery(): array
    {
        if ($this->updateMask === null) {
            return [];
        }

        return ['update_mask' => $this->updateMask];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
