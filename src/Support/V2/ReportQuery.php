<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

/**
 * Query builder for Keap v2 Reporting API - List Reports endpoint
 *
 * Provides report-specific filter validation and helpers for the
 * List Reports endpoint via dynamic method calls.
 *
 * @method $this byName(string $name) Filter by report name
 * @method $this bySinceCreatedTime(string $datetime) Filter by created since datetime
 * @method $this byUntilCreatedTime(string $datetime) Filter by created until datetime
 * @method $this orderByName(string $direction = 'asc') Order by report name
 * @method $this orderByCreatedTime(string $direction = 'asc') Order by creation time
 */
class ReportQuery extends Query
{
    /**
     * Allowed filter fields for reports list endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'name',
        'since_created_time',
        'until_created_time',
    ];

    /**
     * Allowed orderBy fields for reports list endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'name',
        'created_time',
    ];

    /**
     * Convenience method: Filter by reports created between two dates
     *
     * @param string $startDatetime Start datetime (ISO 8601 format)
     * @param string $endDatetime End datetime (ISO 8601 format)
     * @return $this
     */
    public function createdBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceCreatedTime($startDatetime)
            ->byUntilCreatedTime($endDatetime);
    }
}
