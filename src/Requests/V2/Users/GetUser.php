<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get User (v2)
 *
 * Retrieves a specific user by ID.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetUser extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $userId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/users/{$this->userId}";
    }
}
