<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Link Contacts (v2)
 *
 * Links two Contacts together using the provided Link type.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class LinkContacts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $contact1Id,
        protected readonly int $contact2Id,
        protected readonly int $linkTypeId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/contacts:link";
    }

    protected function defaultBody(): array
    {
        return [
            'contact1_id' => (string) $this->contact1Id,
            'contact2_id' => (string) $this->contact2Id,
            'link_type_id' => (string) $this->linkTypeId,
        ];
    }
}
