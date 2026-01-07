<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteNote extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $contactId,
        protected readonly int $noteId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/contacts/{$this->contactId}/notes/{$this->noteId}";
    }
}
