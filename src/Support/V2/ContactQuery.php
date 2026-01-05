<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\ContactFieldSelector;

/**
 * Query builder for Keap v2 Contacts API
 *
 * Provides contact-specific filter validation and helpers for the
 * List Contacts endpoint via dynamic method calls.
 *
 * @method $this byEmail(string $email) Filter by email address
 * @method $this byGivenName(string $name) Filter by given name (first name)
 * @method $this byFamilyName(string $name) Filter by family name (last name)
 * @method $this byCompanyId(string $id) Filter by company ID
 * @method $this byContactIds(array $ids) Filter by specific contact IDs
 * @method $this byStartUpdateTime(string $datetime) Filter by start update time
 * @method $this byEndUpdateTime(string $datetime) Filter by end update time
 * @method $this orderById(string $direction = 'asc') Order by contact ID
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByEmail(string $direction = 'asc') Order by email address
 */
class ContactQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new ContactFieldSelector;
    }

    /**
     * Allowed filter fields for contacts endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'email',
        'given_name',
        'family_name',
        'company_id',
        'contact_ids',
        'start_update_time',
        'end_update_time',
    ];

    /**
     * Allowed orderBy fields for contacts endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'create_time',
        'email',
    ];

    /**
     * Convenience method: Filter by contacts updated between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function updatedBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->byStartUpdateTime($startDatetime)
            ->byEndUpdateTime($endDatetime);
    }
}
