<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\CompanyFieldSelector;

/**
 * Query builder for Keap v2 Companies API
 *
 * Provides company-specific filter validation and helpers for the
 * List Companies endpoint via dynamic method calls.
 *
 * @method $this byCompanyName(string $name) Filter by company name
 * @method $this byEmail(string $email) Filter by email address
 * @method $this bySinceTime(string $datetime) Filter by records since datetime
 * @method $this byUntilTime(string $datetime) Filter by records until datetime
 * @method $this orderById(string $direction = 'asc') Order by company ID
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByName(string $direction = 'asc') Order by company name
 * @method $this orderByEmail(string $direction = 'asc') Order by email address
 */
class CompanyQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new CompanyFieldSelector;
    }

    /**
     * Allowed filter fields for companies endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'company_name',
        'email',
        'since_time',
        'until_time',
    ];

    /**
     * Allowed orderBy fields for companies endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'create_time',
        'name',
        'email',
    ];

    /**
     * Convenience method: Filter by companies created between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function createdBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceTime($startDatetime)
            ->byUntilTime($endDatetime);
    }
}
