<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetContactTags extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly int $contactId, protected readonly ?int $limit = null, protected readonly ?int $offset = null) {}

    public function resolveEndpoint(): string
    {
        return "/v1/contacts/{$this->contactId}/tags";
    }

    protected function defaultQuery(): array
    {
        $q = [];
        if ($this->limit) {
            $q['limit'] = $this->limit;
        }
        if ($this->offset) {
            $q['offset'] = $this->offset;
        }

        return $q;
    }
}
