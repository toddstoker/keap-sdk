<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

/**
 * Query builder for Keap v1 Tags API
 *
 * Provides tag-specific filter validation and helpers for the
 * List Tags endpoint via dynamic method calls.
 *
 * @method $this byCategory(int $categoryId) Filter by tag category ID
 * @method $this byName(string $name) Filter by tag name
 */
class TagQuery extends Query
{
    /**
     * Allowed filter fields for tags endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'category',
        'name',
    ];

    /**
     * Allowed orderBy fields for tags endpoint
     *
     * Note: The v1 tags endpoint doesn't explicitly support orderBy in the API spec,
     * but we include common fields that may be supported or added in the future.
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [];
}
