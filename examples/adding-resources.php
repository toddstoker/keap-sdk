<?php

declare(strict_types=1);

/**
 * Adding New Resources Example
 *
 * This example demonstrates how to add new resources to the SDK
 * using the ResourceFactory pattern.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Toddstoker\KeapSdk\Credentials\PersonalAccessToken;
use Toddstoker\KeapSdk\Keap;

// ============================================================================
// Step 1: Add to ResourceFactory::$classMap
// ============================================================================

/*
In src/Resources/ResourceFactory.php, add your resource mapping:

private static array $classMap = [
    'contacts' => [
        1 => \Toddstoker\KeapSdk\Resources\V1\ContactsResource::class,
        2 => \Toddstoker\KeapSdk\Resources\V2\ContactsResource::class,
    ],
    'companies' => [
        1 => \Toddstoker\KeapSdk\Resources\V1\CompaniesResource::class,
        2 => \Toddstoker\KeapSdk\Resources\V2\CompaniesResource::class,
    ],
    'tags' => [
        1 => \Toddstoker\KeapSdk\Resources\V1\TagsResource::class,
        2 => \Toddstoker\KeapSdk\Resources\V2\TagsResource::class,
    ],
];
*/

// ============================================================================
// Step 2: Create Resource Classes
// ============================================================================

/*
Example: src/Resources/V1/CompaniesResource.php

<?php

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Data\Company;
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Companies\GetCompany;
use Toddstoker\KeapSdk\Requests\V1\Companies\ListCompanies;

class CompaniesResource
{
    public function __construct(
        protected readonly Keap $connector
    ) {}

    public function get(int $companyId): Company
    {
        $response = $this->connector->send(new GetCompany($companyId));
        return Company::fromArray($response->json());
    }

    public function list(int $limit = 100, int $offset = 0): array
    {
        $response = $this->connector->send(new ListCompanies($limit, $offset));
        return array_map(
            fn (array $data) => Company::fromArray($data),
            $response->json('companies')
        );
    }
}
*/

// ============================================================================
// Step 3: Use the New Resource
// ============================================================================

$keap = new Keap(new PersonalAccessToken('your-token'));

// The magic __call() method routes to ResourceFactory
// which creates and caches the CompaniesResource instance
try {
    // Access v2 contact resource (default)
    $companies = $keap->contacts()->list();

    // Access v1 contact resource
    $companiesV1 = $keap->contacts(1)->list();

    // Get specific contact
    $company = $keap->contacts()->get(123);

    echo "Successfully accessed contacts resource!\n";
} catch (\InvalidArgumentException $e) {
    echo 'Error: '.$e->getMessage()."\n";
    // This will show available resources if the resource doesn't exist
}

// ============================================================================
// How the Magic Works
// ============================================================================

/*
When you call $keap->companies():

1. PHP's magic __call() method is triggered in the Keap class
2. __call() creates/retrieves the ResourceFactory instance
3. ResourceFactory::get() is called with:
   - Resource name: 'companies'
   - Version: from argument or default apiVersion
4. Factory checks $classMap for the resource
5. Factory creates new instance or returns cached one
6. Resource is returned for method chaining

The key code in Keap::__call():

public function __call(string $name, array $arguments)
{
    $this->resourceFactory ??= new ResourceFactory($this);

    $argumentVersion = isset($arguments[0]) && is_int($arguments[0])
        ? $arguments[0]
        : null;

    return $this->resourceFactory->get($name, $this->whichVersion($argumentVersion));
}
*/

// ============================================================================
// Advanced: Multiple Resource Instances
// ============================================================================

$keap = new Keap(new PersonalAccessToken('your-token'));

// Each version gets its own cached instance
$contactsV1 = $keap->contacts(1);  // Creates and caches v1 instance
$contactsV2 = $keap->contacts(2);  // Creates and caches v2 instance

// Subsequent calls reuse cached instances
$sameContactsV1 = $keap->contacts(1);  // Returns cached v1 instance
$sameContactsV2 = $keap->contacts(2);  // Returns cached v2 instance

// This is efficient - no duplicate instances created
assert($contactsV1 === $sameContactsV1);
assert($contactsV2 === $sameContactsV2);

echo "Resource caching working correctly!\n";
