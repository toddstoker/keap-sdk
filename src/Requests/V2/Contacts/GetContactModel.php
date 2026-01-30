<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;

/**
 * Get Contact Model (v2)
 *
 * Get the custom fields and optional properties for the Contact object.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetContactModel extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;
    
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v2/contacts/model';
    }
}
