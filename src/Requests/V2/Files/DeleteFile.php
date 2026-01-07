<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteFile extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $fileId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/files/{$this->fileId}";
    }
}
