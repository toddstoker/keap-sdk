<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Toddstoker\KeapSdk\Credentials\BaseCredential;
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
 * @method \Toddstoker\KeapSdk\Resources\V1\ContactsResource|\Toddstoker\KeapSdk\Resources\V2\ContactsResource contacts(?int $version = null) Access the Contacts resource
 *
 */
class Keap extends Connector
{
    use AcceptsJson;

    /**
     * Resource factory instance for managing resource instantiation
     *
     * @var \Toddstoker\KeapSdk\Resources\ResourceFactory
     */
    protected ResourceFactory $resourceFactory;

    /**
     * Initialize the Keap SDK
     *
     * @param BaseCredential $credential Authentication credential (OAuth, PersonalAccessToken, or ServiceKey)
     * @param int $apiVersion API version to use (1 or 2, defaults to 2)
     */
    public function __construct(
        protected readonly BaseCredential $credential,
        public int $apiVersion = 2
    ) {
    }

    /**
     * Magic method to dynamically access API resources
     *
     * Provides fluent access to resources like contacts(), companies(), etc.
     * Resources are created and cached by the ResourceFactory.
     *
     * @param string $name Resource name (e.g., 'contacts', 'companies', 'tags')
     * @param array{0?: int} $arguments Optional array with API version as first element
     * @return object The requested resource instance
     * @throws \InvalidArgumentException If resource doesn't exist for the specified version
     *
     */
    public function __call(string $name, array $arguments)
    {
        if(!isset($this->resourceFactory)){
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
     * Dynamically constructs the base URL using the configured API version.
     *
     * @return string Base URL for the API (e.g., "https://api.infusionsoft.com/crm/rest/v2")
     */
    public function resolveBaseUrl(): string
    {
        return "https://api.infusionsoft.com/crm/rest/v{$this->apiVersion}";
    }

    /**
     * Define default headers for all requests
     *
     * Sets standard headers for JSON communication with the Keap API.
     *
     * @return array<string, string> Array of header key-value pairs
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'keap-sdk-php/1.0',
        ];
    }

    /**
     * Define default authentication for all requests
     *
     * Delegates to the credential's getAuth() method to return the
     * appropriate SaloonPHP authenticator (typically TokenAuthenticator).
     *
     * @return Authenticator SaloonPHP authenticator instance
     * @throws \Toddstoker\KeapSdk\Exceptions\AuthenticationException If credential is invalid or incomplete
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
     * @param int|null $version Optional version override
     * @return int The API version to use (1 or 2)
     */
    protected function whichVersion(?int $version = null): int
    {
        return $version ?? $this->apiVersion;
    }
}
