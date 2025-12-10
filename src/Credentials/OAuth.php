<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Credentials;

use Saloon\Http\Auth\TokenAuthenticator;
use Toddstoker\KeapSdk\Exceptions\AuthenticationException;

class OAuth implements BaseCredential
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $redirectUri,
        protected ?string $accessToken = null,
        protected ?string $refreshToken = null,
    ) { }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @throws \Toddstoker\KeapSdk\Exceptions\AuthenticationException
     */
    public function getAuth(): TokenAuthenticator
    {
        $accessToken = $this->getAccessToken();

        if ($accessToken === null) {
            throw new AuthenticationException(
                'OAuth access token is not set.'
            );
        }

        return new TokenAuthenticator($accessToken);
    }
}