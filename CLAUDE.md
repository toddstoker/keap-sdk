# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `keap-sdk`, a PHP SDK for interacting with Keap's REST API (both v1 and v2). The SDK is built on **SaloonPHP 3.x**, a modern PHP library for building API integrations with a focus on developer experience, type safety, and testability.

**Project Goals:**
- Provide a type-safe, modern PHP interface to Keap's API
- Support both REST API v1 and v2 endpoints
- Support OAuth2 authorization code flow with token refresh
- Implement rate limiting and retry logic
- Provide excellent IDE autocomplete support
- Maintain comprehensive test coverage
- Follow PSR standards and PHP best practices

**Links:**
- Keap REST API v1 Docs: https://developer.infusionsoft.com/docs/rest/
  - This link includes content that is loaded dynamically. An OpenAPI specification is available here: https://developer.infusionsoft.com/docs/rest/2025-11-05-v1.json  
- Keap REST API v2 Docs: https://developer.infusionsoft.com/docs/restv2/
  - This link includes content that is loaded dynamically. An OpenAPI specification is available here: https://developer.keap.com/docs/restv2/2025-11-05-v2.json
- SaloonPHP Docs: https://docs.saloon.dev/

## Technology Stack

- **PHP:** 8.4+ (leveraging modern PHP features like typed properties, constructor property promotion, etc.)
- **SaloonPHP:** 3.x for HTTP client architecture
- **PSR Standards:** PSR-4 (autoloading), PSR-12 (coding style)
- **Namespace:** `Toddstoker\KeapSdk`

## Architecture & Directory Structure

The SDK follows SaloonPHP's recommended architecture pattern:

```
src/
├── Keap.php                          # Main SDK entry point / Connector
├── Credentials/
│   ├── BaseCredential.php           # Base credential interface
│   ├── OAuth.php                    # OAuth2 credentials
│   ├── PersonalAccessToken.php      # PAT credentials
│   └── ServiceKey.php               # Service account key credentials
├── Resources/
│   ├── ResourceFactory.php          # Factory for dynamic resource instantiation
│   ├── V1/                           # REST API v1 resources
│   │   ├── ContactsResource.php
│   │   ├── CompaniesResource.php
│   │   ├── TagsResource.php
│   │   ├── OpportunitiesResource.php
│   │   └── ...
│   └── V2/                           # REST API v2 resources
│       └── ...
├── Requests/
│   ├── V1/                           # v1 API requests
│   │   ├── Contacts/
│   │   │   ├── GetContact.php
│   │   │   ├── ListContacts.php
│   │   │   ├── CreateContact.php
│   │   │   ├── UpdateContact.php
│   │   │   └── DeleteContact.php
│   │   └── ...
│   └── V2/                           # v2 API requests
│       └── ...
├── Responses/
│   ├── KeapResponse.php             # Base response class
│   └── ...
├── Exceptions/
│   ├── KeapException.php            # Base exception
│   ├── AuthenticationException.php
│   ├── RateLimitException.php
│   ├── ValidationException.php
│   └── ...
├── Middleware/
│   ├── RateLimitMiddleware.php      # Handle Keap's rate limits
│   └── ErrorHandlingMiddleware.php  # Standardized error handling
└── Support/
    ├── Pagination/
    │   └── KeapPaginator.php
    └── ...

tests/
├── Unit/
│   ├── Auth/
│   ├── Requests/
│   └── ...
├── Feature/
│   └── ...
└── Fixtures/                         # Mock API responses for testing
    ├── V1/
    └── V2/
```

## Core Concepts

### 1. The Keap Connector

The `Keap` class extends SaloonPHP's `Connector` and serves as the main entry point:

**Key Responsibilities:**
- Define base URLs for v1 (`https://api.infusionsoft.com/crm/rest/v1`) and v2 (`https://api.infusionsoft.com/crm/rest/v2`)
- Accept a `BaseCredential` instance (OAuth, PersonalAccessToken, or ServiceKey)
- Delegate authentication to credential classes via `getAuth()` method
- Configure default headers (Content-Type, Accept, User-Agent)
- Provide dynamic resource access via `__call()` magic method
- Manage resource instantiation through `ResourceFactory`
- Implement OAuth2 authorization code flow via `AuthorizationCodeGrant` trait
- Configure OAuth2 endpoints in `defaultOauthConfig()` method

**Example:**
```php
$keap = new Keap(new PersonalAccessToken('token'), apiVersion: 2);

// Dynamic resource access with version selection
$contact = $keap->contacts()->get(123);      // Uses default v2
$contactV1 = $keap->contacts(1)->get(123);   // Explicitly use v1
```

### 2. ResourceFactory

The `ResourceFactory` manages resource instantiation and caching:

**Features:**
- **Dynamic instantiation:** Creates resources based on name and version
- **Instance caching:** Reuses resource instances for efficiency
- **Version management:** Maintains separate instances per version
- **Centralized mapping:** `$classMap` defines all available resources

**Adding New Resources:**
```php
// In ResourceFactory::$classMap
private static array $classMap = [
    'contacts' => [
        1 => \Toddstoker\KeapSdk\Resources\V1\ContactsResource::class,
        2 => \Toddstoker\KeapSdk\Resources\V2\ContactsResource::class,
    ],
    'companies' => [
        1 => \Toddstoker\KeapSdk\Resources\V1\CompaniesResource::class,
        2 => \Toddstoker\KeapSdk\Resources\V2\CompaniesResource::class,
    ],
];
```

### 3. Resources

Resource classes group related requests and provide a fluent API:

**Structure:**
- Accept `Keap` connector in constructor
- Provide methods for CRUD operations
- Return structured arrays from API responses
- Use descriptive method names

**Example:**
```php
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Credentials\PersonalAccessToken;

$keap = new Keap(new PersonalAccessToken('your-token'));

// Access resources dynamically
$contact = $keap->contacts()->get($contactId);
$contacts = $keap->contacts()->list(limit: 100);

// Override version per call
$contactV1 = $keap->contacts(1)->get($contactId);
```

### 4. Credentials

Credential classes encapsulate authentication methods and implement `BaseCredential`:

**Interface:**
```php
interface BaseCredential {
    public function getAuth(): ?Authenticator;
}
```

**Implementation Pattern:**
Each credential class:
- Stores authentication data (tokens, keys, client credentials)
- Returns appropriate SaloonPHP authenticator via `getAuth()`
- Handles credential-specific validation
- OAuth is fully immutable (readonly) - new instances are created when tokens change
- All OAuth properties are public for direct access

**Examples:**
```php
// Personal Access Token (immutable, readonly)
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

// OAuth (fully immutable readonly credential)
readonly class OAuth implements BaseCredential
{
    public function __construct(
        public string $clientId,
        public string $clientSecret,
        public string $redirectUri,
        public ?string $accessToken = null,
        public ?string $refreshToken = null,
        public ?DateTimeImmutable $expiresAt = null
    ) {}

    public function getAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->accessToken ?? '');
    }
}
```

**Key Design Notes:**
- OAuth credential is completely immutable - once created, it cannot be modified
- When tokens are refreshed, a new OAuth instance is created
- Access token properties directly (e.g., `$oauth->accessToken`, not getter methods)
- The SDK manages credential replacement during OAuth flow and token refresh

### 5. Requests

Request classes represent individual API operations. Each request should:
- Extend `Saloon\Http\Request`
- Define the HTTP method and endpoint
- Declare query parameters, body data, and headers
- Implement `resolveEndpoint()` for the URL path
- Use typed properties for parameters

### 6. Authentication

Keap supports three authentication methods via credential classes:

**OAuth2 (`OAuth`)** - For user-based access:
- Requires clientId, clientSecret, and redirectUri
- Implements full OAuth2 authorization code flow via SaloonPHP's `AuthorizationCodeGrant` trait
- Uses Keap's OAuth2 endpoints:
  - Authorization: `https://accounts.infusionsoft.com/app/oauth/authorize`
  - Token: `https://api.infusionsoft.com/token`
  - User Info: `https://api.infusionsoft.com/crm/rest/v1/oauth/connect/userinfo`
- Scope: `full` (currently the only supported scope)
- OAuth credential is fully immutable (readonly class)
- New OAuth instances are created when tokens are obtained or refreshed
- Access tokens must be obtained through the authorization flow
- Token refresh is handled by `refreshToken()` method which returns a new OAuth instance
- Access token properties directly (e.g., `$oauth->accessToken`, `$oauth->refreshToken`, `$oauth->expiresAt`)
- Store tokens securely (never commit real tokens to version control)

**OAuth2 Flow Steps:**
```php
// 1. Create OAuth credential and connector
$oauth = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/callback'
);
$keap = new Keap($oauth);

// 2. Generate authorization URL
$authUrl = $keap->getAuthorizationUrl();
$state = $keap->getState(); // Store for verification

// 3. Redirect user to $authUrl
// User authorizes and is redirected back with code and state

// 4. Exchange authorization code for access token
$keap->getAccessToken($code, $state, $expectedState);
// The SDK automatically creates a new OAuth credential with tokens

// 5. Save tokens to your database
// Access properties directly (OAuth is readonly)
$accessToken = $keap->credential->accessToken;
$refreshToken = $keap->credential->refreshToken;
$expiresAt = $keap->credential->expiresAt;
// saveToDatabase($accessToken, $refreshToken, $expiresAt);

// 6. Make API calls
$contact = $keap->contacts()->get(123);

// 7. Restore OAuth session from stored tokens
$oauth = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/callback',
    accessToken: $storedAccessToken,
    refreshToken: $storedRefreshToken,
    expiresAt: $storedExpiresAt
);
$keap = new Keap($oauth);

// 8. Refresh token when expired
if ($oauth->expiresAt < new DateTimeImmutable()) {
    // Returns a NEW OAuth credential instance with updated tokens
    $updatedCredential = $keap->refreshToken();

    // Save new tokens to database (direct property access)
    $newAccessToken = $updatedCredential->accessToken;
    $newRefreshToken = $updatedCredential->refreshToken;
    $newExpiresAt = $updatedCredential->expiresAt;
    // saveToDatabase($newAccessToken, $newRefreshToken, $newExpiresAt);
}
```

**Important Notes:**
- OAuth credential is completely immutable (readonly class)
- A NEW OAuth instance is created each time tokens are obtained or refreshed
- Keap returns a NEW refresh token with each token refresh
- Always update stored refresh tokens after calling `refreshToken()`
- Access token properties directly: `$oauth->accessToken`, `$oauth->refreshToken`, `$oauth->expiresAt`
- The `refreshToken()` method returns a new OAuth credential instance
- Consider using encrypted storage for tokens in production

**Personal Access Token (`PersonalAccessToken`)** - For server-to-server access:
- Long-lived tokens for backend integrations
- Recommended for most server-side use cases
- Uses SaloonPHP's `TokenAuthenticator` with Bearer token
- See: https://developer.infusionsoft.com/pat-and-sak/

**Service Account Key (`ServiceAccountKey`)** - For service-based access:
- Machine-to-machine authentication
- Uses SaloonPHP's `TokenAuthenticator` with Bearer token
- See: https://developer.infusionsoft.com/pat-and-sak/

All credential classes implement the `BaseCredential` interface and use Bearer token authentication via the `Authorization` header.

### 7. Error Handling

The SDK implements comprehensive error handling through the `RequestExceptionHandler` middleware:

**Exception Hierarchy:**
```php
RequestException (base, extends Saloon\Exceptions\Request\RequestException)
├── ClientException (4xx errors)
│   ├── UnauthorizedException (401)
│   ├── ForbiddenException (403)
│   ├── NotFoundException (404)
│   └── TooManyRequestsException (429)
└── ServerException (5xx errors)
    └── InternalServerErrorException (500)
```

**Features:**
- All exceptions extend `RequestException` which extends SaloonPHP's `RequestException`
- Automatic status code to exception mapping via middleware
- Access to HTTP response via `$exception->getResponse()` method
- Extract API error messages via `$exception->getApiMessage()` method
- Rate limit exceptions include `retryAfter` property extracted from headers
- Unmapped status codes fall back to appropriate base exception (ClientException, ServerException, or RequestException)

**Implementation Pattern:**

```php
use Toddstoker\KeapSdk\Exceptions\ClientException\ClientException;
use Toddstoker\KeapSdk\Exceptions\ClientException\NotFoundException;
use Toddstoker\KeapSdk\Exceptions\ClientException\TooManyRequestsException;
use Toddstoker\KeapSdk\Exceptions\ClientException\UnauthorizedException;

try {
    $contact = $keap->contacts()->get(123);

} catch (UnauthorizedException $e) {
    // Authentication failed - check credentials
    $message = $e->getApiMessage(); // Get error message from API
    $response = $e->getResponse();

} catch (NotFoundException $e) {
    // Resource not found - check ID
    $response = $e->getResponse();
    $statusCode = $response->status(); // 404

} catch (TooManyRequestsException $e) {
    // Rate limit exceeded - wait and retry
    if ($e->retryAfter) {
        sleep($e->retryAfter);
        // Retry the request
    }

} catch (ClientException $e) {
    // Other client errors (4xx)
    $message = $e->getApiMessage();
    $statusCode = $e->getResponse()->status();
}
```

**Accessing Last Response:**
The SDK stores the last HTTP response (even when exceptions are thrown) for debugging:

```php
use Toddstoker\KeapSdk\Exceptions\RequestException;

try {
    $contact = $keap->contacts()->get(123);
} catch (RequestException $e) {
    // Access response from exception
    $response = $e->getResponse();

    // Or access from connector (same response)
    $response = $keap->lastResponse;

    // Inspect headers, body, etc.
    $headers = $response->headers();
    $body = $response->json();
}
```

### 8. Rate Limiting

Keap enforces rate limits:
- Default: 125 requests per second
- Respect `X-RateLimit-*` headers
- Implement exponential backoff for 429 responses
- Consider using SaloonPHP's rate limit plugin

### 9. V2 Query Builder and Pagination

The SDK provides a powerful query builder for v2 endpoints with integrated filtering, sorting, pagination, and field selection.

**Architecture:**
- **Base `Query` class** - Abstract base with dynamic method handling via `__call()`
- **Resource-specific queries** - `ContactQuery`, `CompanyQuery`, etc. extend `Query`
- **`Paginator` class** - Handles cursor-based pagination automatically

**Key Features:**
- Fluent, chainable API for building complex queries
- Dynamic method generation: `by{FieldName}()` for filters, `orderBy{FieldName}()` for sorting
- Validation against allowed filters and orderBy fields per resource
- Automatic conversion of camelCase method names to snake_case API field names
- Support for cursor-based pagination (v2) via `Paginator`

#### Query Builder Usage

**Basic Filtering:**
```php
use Toddstoker\KeapSdk\Support\V2\ContactQuery;

$query = ContactQuery::make()
    ->byEmail('john@example.com')
    ->byCompanyId('123')
    ->pageSize(100);

$result = $keap->contacts()->list($query);
```

**Complex Queries:**
```php
$query = ContactQuery::make()
    ->byCompanyId('123')
    ->updatedBetween('2025-01-01', '2025-12-31')  // Convenience method
    ->orderByCreateTime('desc')
    ->fields(['id', 'email_addresses', 'given_name', 'family_name', 'update_time'])
    ->pageSize(50);

$result = $keap->contacts()->list($query);
```

**Direct Methods (Alternative Syntax):**
```php
$query = ContactQuery::make()
    ->where('email', 'john@example.com')
    ->where('company_id', '123')
    ->orderBy('create_time', 'desc')
    ->fields(['id', 'email_addresses'])
    ->pageSize(100);
```

**Dynamic Methods:**
```php
// These methods are generated dynamically via __call()
$query->byEmail('test@example.com');          // Calls where('email', 'test@example.com')
$query->byGivenName('John');                   // Calls where('given_name', 'John')
$query->byContactIds([1, 2, 3]);              // Handles arrays automatically
$query->orderByCreateTime('desc');             // Calls orderBy('create_time', 'desc')
```

#### Pagination

**Single Page:**
```php
$result = $keap->contacts()->list(
    ContactQuery::make()->pageSize(100)
);

// Access results
$contacts = $result['contacts'];           // Array of contact data arrays
$nextToken = $result['next_page_token'];   // Token for next page (if available)

// Fetch next page
if ($nextToken) {
    $result = $keap->contacts()->list(
        ContactQuery::make()
            ->pageSize(100)
            ->pageToken($nextToken)
    );
}
```

**Automatic Pagination with Paginator:**
```php
// Iterate through all contacts automatically
$paginator = $keap->contacts()->paginate(
    ContactQuery::make()
        ->byCompanyId('123')
        ->pageSize(100)
);

foreach ($paginator->items('contacts') as $contactData) {
    // $contactData is an array with contact data
    echo $contactData['email_addresses'][0]['email'] ?? 'No email';
}
```

**Paginator Methods:**
```php
// Iterate through items across all pages
foreach ($paginator->items('contacts') as $item) { }

// Iterate through pages (not individual items)
foreach ($paginator->pages() as $page) { }

// Manual page control
$page1 = $paginator->getPage();
$page2 = $paginator->nextPage();
$hasMore = $paginator->hasMorePages();

// Get all items as array (WARNING: loads everything into memory)
$allContacts = $paginator->all('contacts');
```

#### Creating Resource-Specific Query Classes

When adding a new v2 resource, extend the `Query` base class:

```php
namespace Toddstoker\KeapSdk\Support\V2;

class CompanyQuery extends Query
{
    // Define allowed filters from API spec
    protected array $allowedFilters = [
        'company_name',
        'email',
        'since_create_time',
        'until_create_time',
    ];

    // Define allowed orderBy fields from API spec
    protected array $allowedOrderBy = [
        'id',
        'create_time',
        'name',
    ];

    // Define allowed fields for field selection from API spec
    protected array $allowedFields = [
        'id',
        'company_name',
        'email_addresses',
        'phone_numbers',
        'create_time',
        'update_time',
        // ... other fields from API spec
    ];

    // Optionally add convenience methods
    public function createdBetween(string $start, string $end): static
    {
        return $this->bySinceCreateTime($start)
            ->byUntilCreateTime($end);
    }
}
```

Now you automatically get:
- **Dynamic filter methods:** `byCompanyName($value)`, `byEmail($value)`, etc.
- **Dynamic orderBy methods:** `orderById($direction)`, `orderByCreateTime($direction)`, etc.
- **Validation against allowed fields:** Filter, orderBy, and field selection are all validated
- **All base methods:** `where()`, `orderBy()`, `fields()`, `pageSize()`, `pageToken()`

**Note on API Versions:**
- **v1 API:** Uses offset-based pagination (`limit`, `offset`)
- **v2 API:** Uses cursor-based pagination (`page_size`, `page_token`)
- The Query builder is designed for v2 endpoints
- v1 resources should use traditional parameter passing

## Keap API Details

### REST API v1

Common endpoints include:
- **Contacts:** CRUD operations, tagging, custom fields, email addresses
- **Companies:** CRUD operations, custom fields
- **Tags:** List, create, apply to contacts
- **Opportunities:** Sales pipeline management
- **Tasks:** Create and manage tasks
- **Emails:** Send emails, manage email templates
- **Products:** Manage products and subscriptions
- **Orders:** E-commerce transactions
- **Appointments:** Calendar and scheduling

### REST API v2

v2 endpoints may offer:
- Improved performance
- Better data structure consistency
- Enhanced filtering and search capabilities
- Bulk operations
- Webhook management

**Note:** When implementing, check both v1 and v2 docs to determine which version offers better functionality for each resource. A preference should be given to v2 when available.

## Coding Standards

### PHP Standards

- Follow **PSR-12** coding style
- Use **strict types** (`declare(strict_types=1);`) in all files
- Leverage PHP 8.4+ features:
  - Constructor property promotion
  - Typed properties
  - Union types
  - Readonly properties
  - Attributes
  - Match expressions
  - Named arguments

### Documentation

- Add PHPDoc blocks to all public methods and properties
- Document exceptions with `@throws` tags
- Do not include usage examples in resource class docblocks. Those belong in the README and examples directory.
- Document OAuth2 setup process in README
- Provide examples for common operations

### Naming Conventions

- **Classes:** PascalCase (e.g., `ContactsResource`, `GetContact`)
- **Methods:** camelCase (e.g., `getContact()`, `listTags()`)
- **Constants:** SCREAMING_SNAKE_CASE (e.g., `API_VERSION`)
- **Properties:** camelCase (e.g., `$accessToken`, `$clientId`)

### Type Safety

- Always use type hints for parameters and return types
- Avoid mixed types when possible
- Use union types for nullable values (e.g., `?string` or `string|null`)
- Validate input data and throw `InvalidArgumentException` for invalid parameters
- Return structured arrays from API responses

## Testing Strategy

### Unit Tests

- Test individual request classes
- Mock connector responses
- Validate request construction (method, endpoint, headers, body)
- Test array response structure from API responses
- Test error handling and exception throwing

### Feature Tests

- Test full request/response cycles using recorded fixtures
- Test authentication flow
- Test pagination logic
- Test rate limiting behavior
- Test retry logic

### Test Doubles

- Use Saloon's `MockClient` for recording and replaying API responses
- Store fixtures in `tests/Fixtures/V1` and `tests/Fixtures/V2`
- Never include real API credentials in tests
- Use factories or builders for test data

## Common Patterns

### Creating a New Resource

1. Create resource class in `src/Resources/V1/` and/or `src/Resources/V2/`
2. Add mapping to `ResourceFactory::$classMap`:
   ```php
   'companies' => [
       1 => \Toddstoker\KeapSdk\Resources\V1\CompaniesResource::class,
       2 => \Toddstoker\KeapSdk\Resources\V2\CompaniesResource::class,
   ],
   ```
3. Create request classes in appropriate subdirectory (`src/Requests/V1/Companies/`)
4. Write tests with mock responses
5. The resource is now accessible via `$keap->companies()`

### Adding a New Request

1. Extend `Saloon\Http\Request`
2. Define HTTP method constant
3. Implement `resolveEndpoint()` method
4. Add constructor parameters for required data
5. Override `defaultQuery()`, `defaultBody()`, or `defaultHeaders()` as needed
6. Add tests

### Example Request Class

```php
<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetContact extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $contactId,
        protected readonly ?array $optionalProperties = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/contacts/{$this->contactId}";
    }

    protected function defaultQuery(): array
    {
        if ($this->optionalProperties === null) {
            return [];
        }

        return [
            'optional_properties' => implode(',', $this->optionalProperties),
        ];
    }
}
```

### Example Resource Class

```php
<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ListContacts;

class ContactsResource
{
    public function __construct(
        protected readonly Keap $connector
    ) {}

    public function get(int $contactId, ?array $optionalProperties = null): array
    {
        $response = $this->connector->send(new GetContact($contactId, $optionalProperties));

        return $response->json();
    }

    public function list(int $limit = 100, int $offset = 0): array
    {
        $response = $this->connector->send(new ListContacts($limit, $offset));
        $data = $response->json();

        return [
            'contacts' => $data['contacts'] ?? [],
            'count' => $data['count'] ?? 0,
            'next' => $data['next'] ?? null,
            'previous' => $data['previous'] ?? null,
        ];
    }
}
```

## Development Workflow

1. **Before implementing a new feature:**
   - Review relevant Keap API documentation
   - Check if endpoint exists in v1, v2, or both
   - Determine which version to implement (prefer v2 if available and feature-complete)
   - Review the API response format

2. **When implementing:**
   - Start with the request class
   - Add resource method that returns arrays
   - Write tests with fixtures
   - Update documentation

3. **Before committing:**
   - Run tests
   - Run static analysis (if configured)
   - Verify code follows PSR-12
   - Update CHANGELOG.md

## Security Considerations

- **Never commit real API credentials** to version control
- Use environment variables for sensitive data
- Validate and sanitize all user input
- Implement CSRF protection for OAuth flows
- Use HTTPS for all API communication
- Store access tokens securely (encrypted at rest)
- Implement token expiration and refresh logic
- Log security events (failed auth attempts, etc.)

## Performance Considerations

- Cache frequently accessed data when appropriate
- Use bulk operations when available
- Implement efficient pagination for large datasets
- Consider using async requests for parallel operations
- Monitor rate limit consumption
- Implement connection pooling for high-volume applications

## Future Considerations

- Implement webhook handling for real-time updates
- Add Laravel service provider for framework integration
- Create facade for simplified usage
- Implement request/response logging
- Add telemetry and metrics
- Consider implementing a query builder for complex filtering
- Add support for batch operations
- Implement automatic retry with exponential backoff
- Add circuit breaker pattern for API resilience

## Questions to Ask When Implementing

1. **Does this endpoint exist in v1, v2, or both?**
2. **What authentication is required?**
3. **What are the rate limits?**
4. **Does this endpoint support pagination?**
5. **What error responses can occur?**
6. **Are there required vs optional parameters?**
7. **What is the expected response structure?**
8. **Should this return an array, collection, or boolean?**
9. **Does this operation have side effects that need logging?**
10. **How should we handle partial failures in batch operations?**

## Resources & References

- **Keap Developer Portal:** https://developer.infusionsoft.com/
- **SaloonPHP Documentation:** https://docs.saloon.dev/
- **OAuth2 RFC:** https://datatracker.ietf.org/doc/html/rfc6749
- **PSR-12:** https://www.php-fig.org/psr/psr-12/
- **PHP 8.4 Release Notes:** https://www.php.net/releases/8.4/

## Changelog Practices

Maintain a CHANGELOG.md following "Keep a Changelog" format:
- **Added** for new features
- **Changed** for changes in existing functionality
- **Deprecated** for soon-to-be removed features
- **Removed** for removed features
- **Fixed** for bug fixes
- **Security** for security-related changes

---

**Last Updated:** 2025-12-09
