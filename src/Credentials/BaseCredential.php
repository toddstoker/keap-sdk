<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Contracts\Authenticator;

interface BaseCredential
{
    /**
     * Get the authenticator for API requests
     *
     * Returns null if credential is not yet ready for authentication
     * (e.g., OAuth credential before access token is obtained).
     *
     * @return Authenticator
     */
    public function getAuth(): Authenticator;
}