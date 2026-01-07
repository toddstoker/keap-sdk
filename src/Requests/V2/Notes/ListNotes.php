<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V2\NoteQuery;

class ListNotes extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId,
        protected readonly ?NoteQuery $queryBuilder = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}/notes";
    }

    protected function defaultQuery(): array
    {
        return $this->queryBuilder?->toArray() ?? [];
    }
}
