<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk;

use Saloon\Contracts\Authenticator;
use Saloon\Contracts\OAuthAuthenticator;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\NullAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;
use Toddstoker\KeapSdk\Credentials\BaseCredential;
use Toddstoker\KeapSdk\Credentials\OAuth;
use Toddstoker\KeapSdk\Middleware\DateFormatterMiddleware;
use Toddstoker\KeapSdk\Middleware\RequestExceptionHandler;
use Toddstoker\KeapSdk\Resources\ResourceFactory;

/**
 * Keap SDK Main Class
 *
 * Provides a simple, fluent interface to the Keap API.
 * Supports both v1 and v2 API endpoints with multiple authentication methods.
 *
 * This class extends SaloonPHP's Connector and uses the magic __call() method
 * to provide dynamic resource access via ResourceFactory.
 *
 * Dynamic resource access methods (pass 1 or 2 for API version, defaults to $apiVersion):
 *
 * @method \Toddstoker\KeapSdk\Resources\V1\ContactsResource|\Toddstoker\KeapSdk\Resources\V2\ContactsResource contacts(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V2\EmailAddressesResource emailAddresses(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V1\FilesResource|\Toddstoker\KeapSdk\Resources\V2\FilesResource files(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V1\HooksResource hooks(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V1\OpportunitiesResource opportunities(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V2\OrdersResource orders(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V2\ReportsResource reports(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V2\SettingsResource settings(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V1\TagsResource|\Toddstoker\KeapSdk\Resources\V2\TagsResource tags(?int $version = null)
 * @method \Toddstoker\KeapSdk\Resources\V2\UsersResource users(?int $version = null)
 */
class Keap extends Connector
{
    use AcceptsJson;
    use AuthorizationCodeGrant {
        createOAuthAuthenticatorFromResponse as parentCreateOAuthAuthenticatorFromResponse;
    }

    protected ?string $response = KeapResponse::class;

    /**
     * Resource factory instance for managing resource instantiation
     */
    protected ResourceFactory $resourceFactory;

    /**
     * The last response received from the API.
     *
     * This is populated automatically by the boot() middleware and is useful
     * for debugging and accessing response data, even when exceptions are thrown.
     *
     * Note: In concurrent request scenarios, this will contain the most recent
     * response from any request on this connector instance.
     */
    protected ?KeapResponse $lastResponse = null;

    /**
     * Initialize the Keap SDK
     *
     * @param  BaseCredential  $credential  Authentication credential (OAuth, PersonalAccessToken, or ServiceKey)
     * @param  int  $apiVersion  API version to use (1 or 2, defaults to 2)
     */
    public function __construct(
        public BaseCredential $credential,
        public int $apiVersion = 2
    ) {}

    /**
     * Magic method to dynamically access API resources
     *
     * Provides fluent access to resources like contacts(), companies(), etc.
     * Resources are created and cached by the ResourceFactory.
     *
     * @param  string  $name  Resource name (e.g., 'contacts', 'companies', 'tags')
     * @param  array<int, mixed>  $arguments  Optional array with API version as first element (int)
     * @return object The requested resource instance
     *
     * @throws \InvalidArgumentException If resource doesn't exist for the specified version
     */
    public function __call(string $name, array $arguments)
    {
        if (! isset($this->resourceFactory)) {
            $this->resourceFactory = new ResourceFactory($this);
        }

        $argumentVersion = isset($arguments[0]) && is_int($arguments[0])
            ? $arguments[0]
            : null;

        return $this->resourceFactory->get($name, $this->whichVersion($argumentVersion));
    }

    /**
     * Resolve the base URL for API requests
     *
     * @return string Base URL for the API (e.g., "https://api.infusionsoft.com/crm/rest")
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.infusionsoft.com/crm/rest';
    }

    /**
     * Refresh the OAuth access token.
     *
     * This method exchanges the current refresh token for a new access token.
     * Because OAuth credentials are immutable (readonly), a NEW OAuth credential
     * instance is created with the updated tokens and replaces the existing credential.
     *
     * The new credential is created internally by createOAuthAuthenticatorFromResponse()
     * when refreshAccessToken() completes successfully.
     *
     * @return OAuth A new OAuth credential instance with updated access and refresh tokens
     *
     * @throws \RuntimeException If credential is not OAuth or refresh token is missing
     */
    public function refreshToken(): OAuth
    {
        if (! ($this->credential instanceof OAuth)) {
            throw new \RuntimeException('Credential object must be instance of OAuth to refresh token.');
        }

        $refreshToken = $this->credential->refreshToken;
        if (empty($refreshToken)) {
            throw new \RuntimeException('Must have a refresh token to refresh access token.');
        }

        // This calls createOAuthAuthenticatorFromResponse() which creates
        // a new OAuth credential and assigns it to $this->credential
        $this->refreshAccessToken($refreshToken);

        // Return the NEW OAuth credential instance created during refresh
        return $this->credential;
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->middleware()
            ->onRequest(new DateFormatterMiddleware)
            ->onResponse(function (Response $response) {
                assert($response instanceof KeapResponse);

                $this->lastResponse = $response;

                return $response;
            })
            ->onResponse(new RequestExceptionHandler);
    }

    /**
     * Define default authentication for all requests
     *
     * Delegates to the credential's getAuth() method to return the
     * appropriate SaloonPHP authenticator.
     *
     * @return Authenticator SaloonPHP authenticator instance
     */
    protected function defaultAuth(): Authenticator
    {
        return $this->credential->getAuth();
    }

    /**
     * Determine which API version to use
     *
     * Returns the specified version parameter if provided, otherwise
     * falls back to the default apiVersion set in the constructor.
     *
     * @param  int|null  $version  Optional version override
     * @return int The API version to use (1 or 2)
     */
    protected function whichVersion(?int $version = null): int
    {
        return $version ?? $this->apiVersion;
    }

    /**
     * Configure OAuth2 settings for Keap
     *
     * Provides OAuth2 configuration for authorization code flow.
     * Uses credentials from the OAuth credential if provided.
     *
     *
     * @return OAuthConfig OAuth2 configuration
     *
     * @throws \RuntimeException If the credential is not an instance of OAuth
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        if (! ($this->credential instanceof OAuth)) {
            throw new \RuntimeException('Credential object must be instance of OAuth to use OAuth2 features.');
        }

        $clientId = $this->credential->clientId;
        $clientSecret = $this->credential->clientSecret;
        $redirectUri = $this->credential->redirectUri;

        return OAuthConfig::make()
            ->setClientId($clientId)
            ->setClientSecret($clientSecret)
            ->setRedirectUri($redirectUri)
            ->setDefaultScopes(['full'])
            ->setAuthorizeEndpoint('https://accounts.infusionsoft.com/app/oauth/authorize')
            ->setTokenEndpoint('https://api.infusionsoft.com/token')
            ->setUserEndpoint('https://api.infusionsoft.com/crm/rest/v1/oauth/connect/userinfo');
    }

    /**
     * Tap into the access token request to adjust authentication to meet Keap's requirements.
     */
    protected function resolveAccessTokenRequest(string $code, OAuthConfig $oauthConfig): Request
    {
        $request = new \Saloon\Http\OAuth2\GetAccessTokenRequest($code, $oauthConfig);

        // Keap doesn't like the Authorization header set during the access token request.
        $request->authenticate(new NullAuthenticator);

        return $request;
    }

    /**
     * Tap into the refresh token request to adjust authentication to meet Keap's requirements.
     */
    protected function resolveRefreshTokenRequest(OAuthConfig $oauthConfig, string $refreshToken): Request
    {
        $authenticator = new TokenAuthenticator(
            base64_encode($oauthConfig->getClientId().':'.$oauthConfig->getClientSecret()),
            'Basic'
        );
        $request = new \Saloon\Http\OAuth2\GetRefreshTokenRequest($oauthConfig, $refreshToken);
        $request->body()->remove('client_id');
        $request->body()->remove('client_secret');
        $request->authenticate($authenticator);

        return $request;
    }

    /**
     * Create OAuth authenticator from token response and update credential.
     *
     * This method is called after successfully obtaining or refreshing OAuth tokens.
     * Because OAuth credentials are immutable (readonly), we create a NEW OAuth
     * credential instance with the updated tokens and replace the existing credential.
     *
     * This method is invoked automatically during:
     * - Initial authorization code exchange (via getAccessToken())
     * - Token refresh (via refreshToken() -> refreshAccessToken())
     *
     * @param  KeapResponse  $response  The OAuth token response
     * @param  string|null  $fallbackRefreshToken  Optional fallback refresh token
     * @return OAuthAuthenticator The authenticator containing the new tokens
     */
    protected function createOAuthAuthenticatorFromResponse(Response $response, ?string $fallbackRefreshToken = null): OAuthAuthenticator
    {
        $authenticator = $this->parentCreateOAuthAuthenticatorFromResponse($response, $fallbackRefreshToken);

        $this->authenticate($authenticator);

        // Ensure we have an OAuth credential (this method is only called during OAuth flows)
        assert($this->credential instanceof OAuth);

        // Create a NEW OAuth credential instance with updated tokens
        // (OAuth is readonly/immutable, so we cannot modify the existing instance)
        $this->credential = new OAuth(
            clientId: $this->credential->clientId,
            clientSecret: $this->credential->clientSecret,
            redirectUri: $this->credential->redirectUri,
            accessToken: $authenticator->getAccessToken(),
            refreshToken: $authenticator->getRefreshToken(),
            expiresAt: $authenticator->getExpiresAt(),
        );

        return $authenticator;
    }

    public function getLastResponse(): ?KeapResponse
    {
        return $this->lastResponse;
    }
}
