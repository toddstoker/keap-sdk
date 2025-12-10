<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;

readonly class ServiceKey implements BaseCredential
{
    public function __construct(
        public string $serviceKey
    ) { }

    public function getAuth(): Authenticator
    {
        return new TokenAuthenticator($this->serviceKey);
    }
}