# Keap SDK for PHP

A modern, type-safe PHP SDK for the Keap REST API (v1 & v2), built on [SaloonPHP](https://docs.saloon.dev/).

[![PHP Version](https://img.shields.io/badge/php-%5E8.4-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

## Features

- 🔐 **Multiple Authentication Methods** - OAuth2, Personal Access Tokens, and Service Account Keys
- 🎯 **Type-Safe** - Full PHP 8.4+ type hints and readonly classes
- 🔄 **Dual API Support** - Seamlessly work with both v1 and v2 APIs
- 🏭 **Resource Factory Pattern** - Dynamic, cached resource instantiation
- 📦 **SaloonPHP Powered** - Built on the robust SaloonPHP HTTP client
- 🎨 **Fluent API** - Intuitive, chainable method calls
- 📚 **Well Documented** - Comprehensive PHPDoc and examples

## Installation

```bash
composer require toddstoker/keap-sdk
```

## Quick Start

```php
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Credentials\PersonalAccessToken;

// Initialize the SDK
$keap = new Keap(new PersonalAccessToken('your-token-here'));

// Get a contact
$contact = $keap->contacts()->get(123);
echo $contact->getFullName();

// List contacts
$result = $keap->contacts()->list(limit: 50);
foreach ($result['contacts'] as $contact) {
    echo $contact->email . "\n";
}

// Create a contact
$newContact = $keap->contacts()->create([
    'given_name' => 'John',
    'family_name' => 'Doe',
    'email_addresses' => [
        ['email' => 'john@example.com', 'field' => 'EMAIL1']
    ]
]);
```

## Authentication

The SDK supports three authentication methods:

### 1. Personal Access Token (Recommended)

Best for server-to-server integrations. [Get your PAT here](https://keys.developer.infusionsoft.com/).

```php
use Toddstoker\KeapSdk\Credentials\PersonalAccessToken;

$keap = new Keap(new PersonalAccessToken('your-pat-token'));
```

### 2. Service Account Key

For machine-to-machine authentication. [Get your SAK here](https://keys.developer.infusionsoft.com/).

```php
use Toddstoker\KeapSdk\Credentials\ServiceAccountKey;

$keap = new Keap(new ServiceAccountKey('your-service-key'));
```

### 3. OAuth2

For user-based access when building applications. The SDK implements the full OAuth2 authorization code flow.

```php
use Toddstoker\KeapSdk\Credentials\OAuth;

// Step 1: Create OAuth credential and connector
$oauth = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/callback'
);

$keap = new Keap($oauth);

// Step 2: Generate authorization URL
$authorizationUrl = $keap->getAuthorizationUrl();
$state = $keap->getState(); // Store this for verification

// Redirect user to $authorizationUrl
// User authorizes and is redirected back with code and state

// Step 3: Exchange authorization code for access token
$authenticator = $keap->getAccessToken(
    code: $_GET['code'],
    state: $_GET['state'],
    expectedState: $storedState
);

// Step 4: Authenticate the connector
$keap->authenticate($authenticator);

// Now you can make API calls
$contact = $keap->contacts()->get(123);

// Step 5: Refresh token when expired
if ($authenticator->hasExpired()) {
    $newAuthenticator = $keap->refreshAccessToken($authenticator);
    $keap->authenticate($newAuthenticator);

    // Important: Store the new tokens (Keap rotates refresh tokens)
    $newAccessToken = $newAuthenticator->getAccessToken();
    $newRefreshToken = $newAuthenticator->getRefreshToken();
}
```

## API Versions

The SDK supports both v1 and v2 of the Keap REST API, with v2 as the default.

```php
// Use v2 (default, recommended)
$keap = new Keap(new PersonalAccessToken('token'));
$contact = $keap->contacts()->get(123);

// Use v1 for entire session
$keapV1 = new Keap(new PersonalAccessToken('token'), apiVersion: 1);

// Override version per resource call
$contactsV1 = $keap->contacts(1);  // Force v1
$contactsV2 = $keap->contacts(2);  // Force v2
```

## Available Resources

### Contacts

```php
// List contacts
$result = $keap->contacts()->list(
    limit: 100,
    offset: 0,
    email: 'john@example.com'
);

// Get a contact
$contact = $keap->contacts()->get(123);

// Create a contact
$contact = $keap->contacts()->create([
    'given_name' => 'John',
    'family_name' => 'Doe',
    'email_addresses' => [
        ['email' => 'john@example.com', 'field' => 'EMAIL1']
    ]
]);

// Update a contact
$contact = $keap->contacts()->update(123, [
    'job_title' => 'Developer'
]);

// Delete a contact
$keap->contacts()->delete(123);

// V1 only: Tag management
$keap->contacts(1)->applyTag(contactId: 123, tagId: 456);
$keap->contacts(1)->removeTag(contactId: 123, tagId: 456);
```

## Architecture

The SDK uses a clean, modern architecture:

- **Keap** - Main connector class extending SaloonPHP's Connector
- **ResourceFactory** - Manages dynamic resource instantiation and caching
- **Credentials** - Encapsulate authentication methods
- **Resources** - Group related API operations (Contacts, Companies, etc.)
- **Requests** - Individual API request classes
- **DTOs** - Type-safe data transfer objects

### How It Works

```php
$keap->contacts()->get(123);

// 1. PHP's __call() magic method is triggered
// 2. ResourceFactory is initialized (if not already)
// 3. Factory looks up 'contacts' in the class map
// 4. ContactsResource is created/retrieved from cache
// 5. get() method is called on the resource
// 6. Request is sent via SaloonPHP
// 7. Response is transformed to Contact DTO
```

## Adding New Resources

1. Create resource classes in `src/Resources/V1/` and/or `src/Resources/V2/`
2. Add mapping to `ResourceFactory::$classMap`:

```php
'companies' => [
    1 => \Toddstoker\KeapSdk\Resources\V1\CompaniesResource::class,
    2 => \Toddstoker\KeapSdk\Resources\V2\CompaniesResource::class,
],
```

3. Resource is now accessible via `$keap->companies()`

See `examples/adding-resources.php` for a detailed guide.

## Error Handling

The SDK provides specific exception classes:

```php
use Toddstoker\KeapSdk\Exceptions\{
    AuthenticationException,
    RateLimitException,
    NotFoundException,
    ValidationException
};

try {
    $contact = $keap->contacts()->get(999999);
} catch (NotFoundException $e) {
    echo "Contact not found: " . $e->getMessage();
} catch (RateLimitException $e) {
    echo "Rate limit exceeded. Retry after: " . $e->retryAfter . " seconds";
} catch (ValidationException $e) {
    echo "Validation failed: " . $e->getMessage();
    print_r($e->errors);
}
```

## Data Transfer Objects

All API responses are transformed into type-safe DTOs:

```php
$contact = $keap->contacts()->get(123);

// Type-safe property access
echo $contact->id;           // int
echo $contact->givenName;    // string|null
echo $contact->email;        // string|null

// Helper methods
echo $contact->getFullName();

// Convert to array
$data = $contact->toArray();
```

## Examples

Check out the `examples/` directory for detailed examples:

- `authentication.php` - All authentication methods
- `adding-resources.php` - How to extend the SDK with new resources

## Requirements

- PHP 8.4 or higher
- Composer
- [SaloonPHP](https://docs.saloon.dev/) 3.x

## Development

### Running Tests

```bash
composer test
```

### Code Style

This project follows PSR-12 coding standards:

```bash
composer cs:fix
```

### Static Analysis

```bash
composer analyse
```

## Documentation

- [Keap REST API v1 Documentation](https://developer.infusionsoft.com/docs/rest/)
- [Keap REST API v2 Documentation](https://developer.keap.com/docs/restv2/)
- [SaloonPHP Documentation](https://docs.saloon.dev/)
- [Project Documentation](CLAUDE.md)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

- **Issues**: [GitHub Issues](https://github.com/toddstoker/keap-sdk/issues)
- **Keap API Support**: [Keap Developer Portal](https://developer.infusionsoft.com/get-support/)

## Acknowledgments

- Built with [SaloonPHP](https://docs.saloon.dev/)
- Inspired by the Keap API ecosystem
- Developed with ☕ and Claude Code

---

**Note**: This SDK is not officially affiliated with Keap. For official support, please visit the [Keap Developer Portal](https://developer.infusionsoft.com/).
