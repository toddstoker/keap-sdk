<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Http\Auth\TokenAuthenticator;

readonly class ServiceAccountKey implements BaseCredential
{
    public function __construct(
        public string $serviceKey
    ) {}

    public function getAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->serviceKey);
    }
}
