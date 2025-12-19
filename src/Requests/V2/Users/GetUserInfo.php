<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get User Info (v2)
 *
 * Retrieves information for the current authenticated end-user.
 *
 * This endpoint follows the OpenID Connect specification for UserInfo.
 *
 * @see http://openid.net/specs/openid-connect-core-1_0.html#UserInfo
 * @see https://developer.keap.com/docs/restv2/
 */
class GetUserInfo extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v2/oauth/connect/userinfo';
    }
}
