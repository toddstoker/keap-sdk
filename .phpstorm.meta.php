<?php

/**
 * PhpStorm Advanced Metadata
 *
 * This file provides type hints for PhpStorm to improve autocomplete and type inference
 * for the Keap SDK's dynamic resource access methods.
 *
 * Note: PhpStorm cannot infer specific return types based on literal argument values
 * (e.g., it can't know that contacts(1) returns V1 vs contacts(2) returning V2).
 * For that level of precision, use PHPStan with our custom extension.
 *
 * What this file DOES provide:
 * - Autocomplete for method names (contacts, tags, etc.)
 * - Union type hints (V1|V2 for dual-version resources)
 * - Autocomplete for the version parameter (1 or 2)
 */

namespace PHPSTORM_META {

    use Toddstoker\KeapSdk\Keap;

    // Register valid API version values for autocomplete
    registerArgumentsSet('keapApiVersions', 1, 2);

    // Map dynamic resource methods to their return types
    override(Keap::__call(0), map([
        // Resources available in both v1 and v2 (union types)
        'contacts' => type(0
            ? \Toddstoker\KeapSdk\Resources\V1\ContactsResource::class
            : \Toddstoker\KeapSdk\Resources\V2\ContactsResource::class
        ),
        'tags' => type(0
            ? \Toddstoker\KeapSdk\Resources\V1\TagsResource::class
            : \Toddstoker\KeapSdk\Resources\V2\TagsResource::class
        ),

        // v2-only resources
        'emailAddresses' => \Toddstoker\KeapSdk\Resources\V2\EmailAddressesResource::class,
        'reporting' => \Toddstoker\KeapSdk\Resources\V2\ReportingResource::class,
        'users' => \Toddstoker\KeapSdk\Resources\V2\UsersResource::class,

        // v1-only resources
        'hooks' => \Toddstoker\KeapSdk\Resources\V1\HooksResource::class,
        'opportunities' => \Toddstoker\KeapSdk\Resources\V1\OpportunitiesResource::class,
    ]));
}
