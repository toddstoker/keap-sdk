<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get User Signature (v2)
 *
 * Retrieves a HTML snippet that contains the user's email signature.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetUserSignature extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $userId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/users/{$this->userId}/signature";
    }
}
