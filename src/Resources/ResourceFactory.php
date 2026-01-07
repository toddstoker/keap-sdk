<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources;

use Toddstoker\KeapSdk\Keap;

/**
 * Resource Factory
 *
 * Manages the creation and caching of resource instances.
 * Resources are created dynamically based on name and API version,
 * and cached to avoid duplicate instantiation.
 *
 * The factory uses a class map to associate resource names with their
 * implementation classes for each API version.
 */
class ResourceFactory
{
    /**
     * Mapping of resource names to their implementation classes by version
     *
     * Add new resources here to make them available through the SDK:
     *
     * ```php
     * 'companies' => [
     *     1 => \Toddstoker\KeapSdk\Resources\V1\CompaniesResource::class,
     *     2 => \Toddstoker\KeapSdk\Resources\V2\CompaniesResource::class,
     * ],
     * ```
     *
     * @var array<string, array<int, class-string>>
     */
    private static array $classMap = [
        'contacts' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\ContactsResource::class,
            2 => \Toddstoker\KeapSdk\Resources\V2\ContactsResource::class,
        ],
        'emailAddresses' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\EmailAddressesResource::class,
        ],
        'files' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\FilesResource::class,
        ],
        'hooks' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\HooksResource::class,
        ],
        'leadSources' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\LeadSourcesResource::class,
        ],
        'notes' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\NotesResource::class,
        ],
        'opportunities' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\OpportunitiesResource::class,
        ],
        'orders' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\OrdersResource::class,
        ],
        'paymentMethods' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\PaymentMethodsResource::class,
        ],
        'reporting' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\ReportingResource::class,
        ],
        'tags' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\TagsResource::class,
            2 => \Toddstoker\KeapSdk\Resources\V2\TagsResource::class,
        ],
        'users' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\UsersResource::class,
        ],
    ];

    /**
     * Cache of instantiated resource objects
     *
     * Key format: "{resourceName}_v{version}"
     *
     * @var array<string, object>
     */
    protected array $instances = [];

    /**
     * Initialize the resource factory
     *
     * @param  Keap  $connector  The Keap connector instance to pass to resources
     */
    public function __construct(
        protected readonly Keap $connector
    ) {}

    /**
     * Get or create a resource instance
     *
     * Returns a cached instance if available, otherwise creates a new one.
     * Each combination of resource name and version gets its own cached instance.
     *
     * @param  string  $name  Resource name (e.g., 'contacts', 'companies')
     * @param  int  $version  API version (1 or 2)
     * @return object The resource instance
     *
     * @throws \InvalidArgumentException If the resource doesn't exist for the specified version
     */
    public function get(string $name, int $version): object
    {
        $key = "{$name}_v{$version}";

        if (! isset($this->instances[$key])) {
            if (! isset(self::$classMap[$name][$version])) {
                throw new \InvalidArgumentException("Resource '{$name}' version {$version} does not exist.");
            }

            $className = self::$classMap[$name][$version];
            $this->instances[$key] = new $className($this->connector);
        }

        return $this->instances[$key];
    }
}
