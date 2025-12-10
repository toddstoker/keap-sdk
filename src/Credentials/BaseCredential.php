<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Contracts\Authenticator;

interface BaseCredential
{
    public function getAuth(): Authenticator;
}