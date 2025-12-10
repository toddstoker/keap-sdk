<?php

declare(strict_types=1);

/**
 * Authentication Examples
 *
 * This file demonstrates the three authentication methods supported by the Keap SDK:
 * 1. Personal Access Token (PAT) - Recommended for server-to-server
 * 2. Service Account Key (SAK) - For service-based access
 * 3. OAuth2 - For user-based access
 *
 * The Keap class extends SaloonPHP's Connector and provides dynamic resource access.
 * Resources are instantiated via the ResourceFactory pattern using magic __call() method.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Credentials\PersonalAccessToken;
use Toddstoker\KeapSdk\Credentials\ServiceKey;
use Toddstoker\KeapSdk\Credentials\OAuth;

// ============================================================================
// Example 1: Personal Access Token (Recommended for most use cases)
// ============================================================================
// PATs are long-lived tokens suitable for server-to-server integrations
// Get your PAT from: https://keys.developer.infusionsoft.com/

$pat = new PersonalAccessToken('your-personal-access-token-here');
$keap = new Keap($pat);

// Now you can make API calls
try {
    $contacts = $keap->contacts()->list(limit: 10);
    echo "Found {$contacts['count']} contacts\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// ============================================================================
// Example 2: Service Account Key
// ============================================================================
// Service keys are for machine-to-machine authentication
// Get your service key from: https://keys.developer.infusionsoft.com/

$serviceKey = new ServiceKey('your-service-account-key-here');
$keapService = new Keap($serviceKey);

try {
    $contact = $keapService->contacts()->get(123);
    echo "Contact: " . $contact->getFullName() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// ============================================================================
// Example 3: OAuth2 (For user-based access)
// ============================================================================
// OAuth2 is used when you need to access user data on their behalf
// Register your app at: https://keys.developer.infusionsoft.com/

$oauth = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/oauth/callback'
);

// After the OAuth flow, you'll receive an access token
// Set it on the OAuth credential:
$oauth->setAccessToken('user-access-token-from-oauth-flow');

// Optionally set the refresh token for token renewal
$oauth->setRefreshToken('user-refresh-token-from-oauth-flow');

$keapOAuth = new Keap($oauth);

try {
    $newContact = $keapOAuth->contacts()->create([
        'given_name' => 'John',
        'family_name' => 'Doe',
        'email_addresses' => [
            [
                'email' => 'john.doe@example.com',
                'field' => 'EMAIL1',
            ],
        ],
    ]);
    echo "Created contact with ID: {$newContact->id}\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// ============================================================================
// Example 4: Using different API versions
// ============================================================================

// Default is v2 (recommended)
$keapV2 = new Keap(new PersonalAccessToken('your-token'));

// Explicitly use v1 by setting apiVersion in constructor
$keapV1 = new Keap(new PersonalAccessToken('your-token'), apiVersion: 1);

// You can also specify version per resource call via magic method
$keap = new Keap(new PersonalAccessToken('your-token'));

// These calls use the __call() magic method to access resources:
$contactsV2 = $keap->contacts();    // Uses default version (2)
$contactsV1 = $keap->contacts(1);   // Override to use v1 for this resource
$contactV2 = $keap->contacts(2);    // Explicitly use v2

// The ResourceFactory caches instances per version
$contact1 = $keap->contacts(1)->get(123);  // Creates v1 ContactsResource
$contact2 = $keap->contacts(1)->get(456);  // Reuses cached v1 instance

// ============================================================================
// Example 5: Error Handling
// ============================================================================

use Toddstoker\KeapSdk\Exceptions\AuthenticationException;
use Toddstoker\KeapSdk\Exceptions\RateLimitException;
use Toddstoker\KeapSdk\Exceptions\NotFoundException;
use Toddstoker\KeapSdk\Exceptions\ValidationException;

try {
    // Create OAuth without setting access token
    $oauthNoToken = new OAuth(
        clientId: 'client-id',
        clientSecret: 'client-secret',
        redirectUri: 'https://example.com/callback'
    );

    // The error occurs when getAuth() is called during authentication
    $keapNoToken = new Keap($oauthNoToken);
    $contacts = $keapNoToken->contacts()->list();
} catch (AuthenticationException $e) {
    echo "Authentication error: " . $e->getMessage() . "\n";
    // Output: OAuth access token is not set.
}

try {
    $contact = $keap->contacts()->get(999999);
} catch (NotFoundException $e) {
    echo "Not found: " . $e->getMessage() . "\n";
}

try {
    // Making too many requests
    for ($i = 0; $i < 200; $i++) {
        $keap->contacts()->list(limit: 1);
    }
} catch (RateLimitException $e) {
    echo "Rate limit exceeded. Retry after: " . $e->retryAfter . " seconds\n";
}

try {
    $contact = $keap->contacts()->create([
        'given_name' => '', // Invalid: empty name
    ]);
} catch (ValidationException $e) {
    echo "Validation failed: " . $e->getMessage() . "\n";
    print_r($e->errors);
}
