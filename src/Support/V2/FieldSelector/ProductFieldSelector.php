<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Products endpoint (v2)
 *
 * Available fields for the GET /v2/products endpoint:
 * - active, categories, city_taxable, country_taxable, description,
 *   inventory, name, options, price, shippable, short_description,
 *   sku, state_taxable, storefront_hidden, subscription_only,
 *   subscription_plans, taxable, weight
 */
class ProductFieldSelector extends FieldSelector
{
    /**
     * @var array<string>
     */
    protected array $allowedFields = [
        'active',
        'categories',
        'city_taxable',
        'country_taxable',
        'description',
        'inventory',
        'name',
        'options',
        'price',
        'shippable',
        'short_description',
        'sku',
        'state_taxable',
        'storefront_hidden',
        'subscription_only',
        'subscription_plans',
        'taxable',
        'weight',
    ];
}
