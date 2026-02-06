<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\ProductFieldSelector;

/**
 * Query builder for Keap v2 Products API
 *
 * Provides product-specific filter validation and helpers for the
 * List Products endpoint via dynamic method calls.
 *
 * @method $this byName(string $name) Filter by product name
 * @method $this orderByName(string $direction = 'asc') Order by product name
 */
class ProductQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new ProductFieldSelector;
    }

    /**
     * Allowed filter fields for products endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'name',
    ];

    /**
     * Allowed orderBy fields for products endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'name',
    ];
}
