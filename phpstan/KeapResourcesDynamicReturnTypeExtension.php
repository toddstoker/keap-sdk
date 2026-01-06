<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Resources\ResourceFactory;

/**
 * PHPStan Dynamic Return Type Extension for Keap Resources
 *
 * This extension provides accurate return types for dynamically called
 * resource methods on the Keap connector class.
 *
 * Examples:
 * - $keap->contacts(1) returns ContactsResource (v1)
 * - $keap->contacts(2) returns ContactsResource (v2)
 * - $keap->contacts() returns ContactsResource v1 or v2 (union type)
 */
class KeapResourcesDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * Resource class mapping (synchronized with ResourceFactory)
     *
     * @var array<string, array<int, class-string>>
     */
    private const CLASS_MAP = [
        'contacts' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\ContactsResource::class,
            2 => \Toddstoker\KeapSdk\Resources\V2\ContactsResource::class,
        ],
        'emailAddresses' => [
            2 => \Toddstoker\KeapSdk\Resources\V2\EmailAddressesResource::class,
        ],
        'hooks' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\HooksResource::class,
        ],
        'opportunities' => [
            1 => \Toddstoker\KeapSdk\Resources\V1\OpportunitiesResource::class,
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

    public function getClass(): string
    {
        return Keap::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        // We handle all magic __call methods on Keap
        return $methodReflection->getName() === '__call';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        // Get the resource name from the method call
        if (! $methodCall->name instanceof \PhpParser\Node\Identifier) {
            return null;
        }

        $resourceName = $methodCall->name->toString();

        // Check if this resource exists in our mapping
        if (! isset(self::CLASS_MAP[$resourceName])) {
            return null;
        }

        $availableVersions = self::CLASS_MAP[$resourceName];

        // Try to determine the version from the first argument
        $version = $this->extractVersionFromArgs($methodCall, $scope);

        if ($version !== null) {
            // We have a literal version number, return specific type
            if (isset($availableVersions[$version])) {
                return new ObjectType($availableVersions[$version]);
            }

            // Invalid version specified
            return null;
        }

        // Version is not known at compile time, return union of all available versions
        $types = array_map(
            fn (string $className) => new ObjectType($className),
            array_values($availableVersions)
        );

        if (count($types) === 1) {
            return $types[0];
        }

        return new UnionType($types);
    }

    /**
     * Extract version number from method call arguments if it's a literal
     *
     * @return int|null Returns the version number if it's a constant, null otherwise
     */
    private function extractVersionFromArgs(MethodCall $methodCall, Scope $scope): ?int
    {
        $args = $methodCall->getArgs();

        if (count($args) === 0) {
            // No version specified, will use default apiVersion at runtime
            // We can't determine it statically, so return null
            return null;
        }

        // Get the type of the first argument
        $argType = $scope->getType($args[0]->value);

        // Check if it's a constant integer
        if ($argType instanceof ConstantIntegerType) {
            return $argType->getValue();
        }

        // Version is not a constant, we can't determine it statically
        return null;
    }
}
