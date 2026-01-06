<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\ContactFieldSelector;

/**
 * Query builder for Keap v2 Tag Contacts API
 *
 * Provides filter validation and helpers for the List Contacts with Tag
 * endpoint via dynamic method calls.
 *
 * @method $this byGivenName(string $name) Filter by given name (first name)
 * @method $this byFamilyName(string $name) Filter by family name (last name)
 * @method $this byEmail(string $email) Filter by email address
 * @method $this bySinceAppliedTime(string $datetime) Filter by start applied time
 * @method $this byUntilAppliedTime(string $datetime) Filter by end applied time
 * @method $this orderByGivenName(string $direction = 'asc') Order by given name
 * @method $this orderByFamilyName(string $direction = 'asc') Order by family name
 * @method $this orderByEmail(string $direction = 'asc') Order by email address
 * @method $this orderByAppliedTime(string $direction = 'asc') Order by applied time
 */
class TagContactQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new ContactFieldSelector;
    }

    /**
     * Allowed filter fields for tag contacts endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'given_name',
        'family_name',
        'email',
        'since_applied_time',
        'until_applied_time',
    ];

    /**
     * Allowed orderBy fields for tag contacts endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'given_name',
        'family_name',
        'email',
        'applied_time',
    ];

    /**
     * Convenience method: Filter by contacts with tag applied between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function appliedBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceAppliedTime($startDatetime)
            ->byUntilAppliedTime($endDatetime);
    }
}
