<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use DateTimeImmutable;
use Saloon\Http\Auth\TokenAuthenticator;

readonly class OAuth implements BaseCredential
{
    public function __construct(
        public string             $clientId,
        public string             $clientSecret,
        public string             $redirectUri,
        public ?string            $accessToken = null,
        public ?string            $refreshToken = null,
        public ?DateTimeImmutable $expiresAt = null
    ) { }

    /**
     * Get Authenticator for API requests.
     * Other Authenticators are used during the OAuth flow.
     */
    public function getAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->accessToken ?? '');
    }
}