<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetNote extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId,
        protected readonly int $noteId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}/notes/{$this->noteId}";
    }
}
