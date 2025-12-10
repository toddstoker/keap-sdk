<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;

class PersonalAccessToken implements BaseCredential
{
    public function __construct(
        public readonly string $personalAccessToken
    ) { }

    public function getAuth(): Authenticator
    {
        return new TokenAuthenticator($this->personalAccessToken);
    }
}