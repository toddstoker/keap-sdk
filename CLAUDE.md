# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `keap-sdk`, a PHP SDK for interacting with Keap's REST API (both v1 and v2). The SDK is built on **SaloonPHP 3.x**, a modern PHP library for building API integrations with a focus on developer experience, type safety, and testability.

**Project Goals:**
- Provide a type-safe, modern PHP interface to Keap's API
- Support both REST API v1 and v2 endpoints
- Handle OAuth2 authentication automatically
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
├── Data/                             # DTOs (Data Transfer Objects)
│   ├── Contact.php
│   ├── Company.php
│   ├── Tag.php
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
- Return DTOs or structured arrays
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
    public function getAuth(): Authenticator;
}
```

**Implementation Pattern:**
Each credential class:
- Stores authentication data (tokens, keys, client credentials)
- Returns appropriate SaloonPHP authenticator via `getAuth()`
- Handles credential-specific validation
- Supports both readonly and mutable credentials

**Example:**
```php
class PersonalAccessToken implements BaseCredential
{
    public function __construct(
        public readonly string $personalAccessToken
    ) {}

    public function getAuth(): Authenticator
    {
        return new TokenAuthenticator($this->personalAccessToken);
    }
}
```

### 5. Requests

Request classes represent individual API operations. Each request should:
- Extend `Saloon\Http\Request`
- Define the HTTP method and endpoint
- Declare query parameters, body data, and headers
- Implement `resolveEndpoint()` for the URL path
- Use typed properties for parameters
- Return appropriate DTO instances via custom response handling

### 6. Data Transfer Objects (DTOs)

DTOs provide type-safe representations of API data:
- Use readonly classes for immutability
- Leverage constructor property promotion
- Implement `fromArray()` static factory methods
- Consider using `toArray()` for serialization
- Document all properties with PHPDoc types

### 7. Authentication

Keap supports three authentication methods via credential classes:

**OAuth2 (`OAuth`)** - For user-based access:
- Requires clientId, clientSecret, and redirectUri
- Access tokens must be set via `setAccessToken()`
- Supports refresh tokens via `setRefreshToken()`
- Uses SaloonPHP's `TokenAuthenticator` with Bearer token
- Store tokens securely (never commit real tokens to version control)

**Personal Access Token (`PersonalAccessToken`)** - For server-to-server access:
- Long-lived tokens for backend integrations
- Recommended for most server-side use cases
- Uses SaloonPHP's `TokenAuthenticator` with Bearer token
- See: https://developer.infusionsoft.com/pat-and-sak/

**Service Account Key (`ServiceKey`)** - For service-based access:
- Machine-to-machine authentication
- Uses SaloonPHP's `TokenAuthenticator` with Bearer token
- See: https://developer.infusionsoft.com/pat-and-sak/

All credential classes implement the `BaseCredential` interface and use Bearer token authentication via the `Authorization` header.

### 8. Error Handling

Implement comprehensive error handling:
- Catch and transform Saloon exceptions into domain-specific exceptions
- Handle common HTTP status codes (401, 403, 404, 422, 429, 500)
- Provide meaningful error messages
- Include API error details when available
- Implement retry logic for transient failures (429, 500, 503)

### 9. Rate Limiting

Keap enforces rate limits:
- Default: 125 requests per second
- Respect `X-RateLimit-*` headers
- Implement exponential backoff for 429 responses
- Consider using SaloonPHP's rate limit plugin

### 10. Pagination

Many Keap endpoints return paginated results:
- v1 typically uses `limit` and `offset` parameters
- Implement cursor-based pagination where supported
- Provide helper methods for iterating through all pages
- Return collection objects that support pagination metadata

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
- Return DTOs instead of raw arrays

## Testing Strategy

### Unit Tests

- Test individual request classes
- Mock connector responses
- Validate request construction (method, endpoint, headers, body)
- Test DTO creation from API responses
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
4. Create DTOs for responses (e.g., `src/Data/Company.php`)
5. Write tests with mock responses
6. The resource is now accessible via `$keap->companies()`

### Adding a New Request

1. Extend `Saloon\Http\Request`
2. Define HTTP method constant
3. Implement `resolveEndpoint()` method
4. Add constructor parameters for required data
5. Override `defaultQuery()`, `defaultBody()`, or `defaultHeaders()` as needed
6. Create corresponding DTO for response
7. Add tests

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

use Toddstoker\KeapSdk\Data\Contact;
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ListContacts;

class ContactsResource
{
    public function __construct(
        protected readonly Keap $connector
    ) {}

    public function get(int $contactId, ?array $optionalProperties = null): Contact
    {
        $response = $this->connector->send(new GetContact($contactId, $optionalProperties));

        return Contact::fromArray($response->json());
    }

    public function list(int $limit = 100, int $offset = 0): array
    {
        $response = $this->connector->send(new ListContacts($limit, $offset));

        return array_map(
            fn (array $data) => Contact::fromArray($data),
            $response->json('contacts')
        );
    }
}
```

## Development Workflow

1. **Before implementing a new feature:**
   - Review relevant Keap API documentation
   - Check if endpoint exists in v1, v2, or both
   - Determine which version to implement (prefer v2 if available and feature-complete)
   - Design the DTO structure based on API response format

2. **When implementing:**
   - Start with the request class
   - Create the DTO
   - Add resource method
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
8. **Should this return a DTO, collection, or boolean?**
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
