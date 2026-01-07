<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\LeadSourceFieldSelector;

/**
 * Query builder for Keap v2 Lead Sources API
 *
 * Provides lead source-specific filter validation and helpers for the
 * List Lead Sources endpoint via dynamic method calls.
 *
 * @method $this byName(string $name) Filter by lead source name
 * @method $this byStatus(string $status) Filter by status (ACTIVE or INACTIVE)
 * @method $this byLeadSourceCategoryId(string $id) Filter by lead source category ID
 * @method $this byVendor(string $vendor) Filter by vendor
 * @method $this byMedium(string $medium) Filter by medium
 * @method $this byStartTime(string $datetime) Filter by start time
 * @method $this byEndTime(string $datetime) Filter by end time
 * @method $this orderByName(string $direction = 'asc') Order by name
 * @method $this orderByStatus(string $direction = 'asc') Order by status
 * @method $this orderByVendor(string $direction = 'asc') Order by vendor
 * @method $this orderByMedium(string $direction = 'asc') Order by medium
 * @method $this orderByStartTime(string $direction = 'asc') Order by start time
 * @method $this orderByEndTime(string $direction = 'asc') Order by end time
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByUpdateTime(string $direction = 'asc') Order by update time
 */
class LeadSourceQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new LeadSourceFieldSelector;
    }

    /**
     * Allowed filter fields for lead sources endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'name',
        'status',
        'lead_source_category_id',
        'vendor',
        'medium',
        'start_time',
        'end_time',
    ];

    /**
     * Allowed orderBy fields for lead sources endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'name',
        'status',
        'vendor',
        'medium',
        'start_time',
        'end_time',
        'create_time',
        'update_time',
    ];

    /**
     * Convenience method: Filter by lead sources active between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function activeBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->byStartTime($startDatetime)
            ->byEndTime($endDatetime);
    }
}
