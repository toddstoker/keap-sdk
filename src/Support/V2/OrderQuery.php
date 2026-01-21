<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\OrderFieldSelector;

/**
 * Query builder for Keap v2 Orders API
 *
 * Provides order-specific filter validation and helpers for the
 * List Orders endpoint via dynamic method calls.
 *
 * @method $this byProductId(int|string $id) Filter by product ID
 * @method $this byContactId(int|string $id) Filter by contact ID
 * @method $this byPaid(bool $paid) Filter by paid status
 * @method $this byCreatedSinceTime(string $datetime) Filter by created since time
 * @method $this byCreatedUntilTime(string $datetime) Filter by created until time
 * @method $this orderById(string $direction = 'asc') Order by order ID
 * @method $this orderByOrderTime(string $direction = 'asc') Order by order time
 */
class OrderQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new OrderFieldSelector;
    }

    /**
     * Allowed filter fields for orders endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'product_id',
        'contact_id',
        'paid',
        'created_since_time',
        'created_until_time',
    ];

    /**
     * Allowed orderBy fields for orders endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'order_time',
    ];

    /**
     * Convenience method: Filter by orders created between two dates
     *
     * @param  string  $sinceTime  Start datetime (ISO 8601 format)
     * @param  string  $untilTime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function createdBetween(string $sinceTime, string $untilTime): static
    {
        return $this->byCreatedSinceTime($sinceTime)
            ->byCreatedUntilTime($untilTime);
    }
}
