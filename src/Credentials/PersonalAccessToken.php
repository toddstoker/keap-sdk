<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Http\Auth\TokenAuthenticator;

readonly class PersonalAccessToken implements BaseCredential
{
    public function __construct(
        public string $personalAccessToken
    ) {}

    public function getAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->personalAccessToken);
    }
}
