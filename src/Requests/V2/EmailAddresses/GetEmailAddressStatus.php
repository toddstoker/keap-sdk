<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\EmailAddresses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Email Address Status (v2)
 *
 * Retrieves the opt-in status for a given Email Address.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetEmailAddressStatus extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $email
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/emailAddresses/{$this->email}/status";
    }
}
