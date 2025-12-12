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
use Toddstoker\KeapSdk\Credentials\ServiceAccountKey;
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

$serviceKey = new ServiceAccountKey('your-service-account-key-here');
$keapService = new Keap($serviceKey);

try {
    $contact = $keapService->contacts()->get(123);
    echo "Contact: " . $contact->getFullName() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// ============================================================================
// Example 3: OAuth2 Authorization Flow (For user-based access)
// ============================================================================
// OAuth2 is used when you need to access user data on their behalf
// Register your app at: https://keys.developer.infusionsoft.com/
//
// The OAuth flow has several steps:
//
// STEP 1: Initialize OAuth credential and create connector
// -------------------------------------------------------
$oauth = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/oauth/callback'
);

$keapOAuth = new Keap($oauth);

// STEP 2: Generate authorization URL and redirect user
// ----------------------------------------------------
// This URL will prompt the user to authorize your app
$authorizationUrl = $keapOAuth->getAuthorizationUrl();
$state = $keapOAuth->getState(); // Store this to verify callback

// In a real app, you would redirect the user to this URL:
// header("Location: {$authorizationUrl}");
// exit;

echo "Authorization URL: {$authorizationUrl}\n";
echo "State (store this): {$state}\n";

// STEP 3: Handle the OAuth callback
// ---------------------------------
// After user authorizes, Keap redirects to your redirect_uri with:
// - code: The authorization code
// - state: The state parameter for verification
//
// In your callback handler (e.g., /oauth/callback route):
// $code = $_GET['code'];
// $returnedState = $_GET['state'];
// $expectedState = /* retrieve stored state */;

// Exchange the authorization code for access token
try {
    // This returns an AccessTokenAuthenticator with the access token,
    // refresh token, and expiry information
    $authenticator = $keapOAuth->getAccessToken(
        code: 'authorization-code-from-callback',
        state: 'returned-state-from-callback',
        expectedState: 'state-you-stored-earlier'
    );

    // Store the tokens for future use
    $accessToken = $authenticator->getAccessToken();
    $refreshToken = $authenticator->getRefreshToken();
    $expiresAt = $authenticator->getExpiresAt();

    echo "Access Token: {$accessToken}\n";
    echo "Refresh Token: {$refreshToken}\n";
    echo "Expires At: {$expiresAt->format('Y-m-d H:i:s')}\n";

    // You should securely store these tokens in your database
    // For example, encrypt them using Laravel's EncryptedOAuthAuthenticatorCast
    // or serialize them: $serialized = $authenticator->serialize();

    // STEP 4: Use the access token for API calls
    // ------------------------------------------
    // Set the authenticator on the connector
    $keapOAuth->authenticate($authenticator);

    // Now you can make authenticated API calls
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

    // STEP 5: Refresh the access token when it expires
    // ------------------------------------------------
    if ($authenticator->hasExpired()) {
        // Refresh the token to get a new access token
        $newAuthenticator = $keapOAuth->refreshAccessToken($authenticator);

        // Update your stored tokens with the new ones
        $newAccessToken = $newAuthenticator->getAccessToken();
        $newRefreshToken = $newAuthenticator->getRefreshToken();
        $newExpiresAt = $newAuthenticator->getExpiresAt();

        // Important: Keap returns a NEW refresh token with each refresh
        // You must update your stored refresh token
        echo "Refreshed! New access token: {$newAccessToken}\n";

        // Use the new authenticator for subsequent requests
        $keapOAuth->authenticate($newAuthenticator);
    }

} catch (\Exception $e) {
    echo "OAuth Error: " . $e->getMessage() . "\n";
}

// STEP 6: Restore a previous OAuth session
// ----------------------------------------
// When you have stored tokens, restore them by passing to the constructor:
$storedAccessToken = 'previously-stored-access-token';
$storedRefreshToken = 'previously-stored-refresh-token';
$storedExpiresAt = new DateTimeImmutable('2025-12-31 23:59:59');

// Create OAuth credential with stored tokens
$oauthRestored = new OAuth(
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://your-app.com/oauth/callback',
    accessToken: $storedAccessToken,
    refreshToken: $storedRefreshToken,
    expiresAt: $storedExpiresAt
);

$keapRestored = new Keap($oauthRestored);

// Now you can make API calls with the restored credential
// The SDK will use the access token automatically
$contacts = $keapRestored->contacts()->list();

// When the token expires, refresh it using the refreshToken() method
if ($oauthRestored->expiresAt < new DateTimeImmutable()) {
    // This returns a new OAuth credential instance with updated tokens
    $updatedCredential = $keapRestored->refreshToken();

    // Save the new tokens to your database
    // Note: OAuth is readonly, so access properties directly
    $newAccessToken = $updatedCredential->accessToken;
    $newRefreshToken = $updatedCredential->refreshToken;
    $newExpiresAt = $updatedCredential->expiresAt;

    // Store these in your database for next time
    // saveToDatabase($newAccessToken, $newRefreshToken, $newExpiresAt);
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

    $keapNoToken = new Keap($oauthNoToken);

    // This will fail because no access token is set
    // The request will be unauthorized (empty token)
    $contacts = $keapNoToken->contacts()->list();
} catch (\Saloon\Exceptions\Request\Statuses\UnauthorizedException $e) {
    echo "Authentication error: Request is not authorized\n";
    echo "Make sure to complete the OAuth flow and set the access token\n";
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
