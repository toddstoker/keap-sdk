<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use Toddstoker\KeapSdk\Support\V1\FieldSelector\ContactFieldSelector;

/**
 * Query builder for Keap v1 Contacts API
 *
 * Provides contact-specific filter validation and helpers for the
 * List Contacts endpoint via dynamic method calls.
 *
 * @method $this byEmail(string $email) Filter by email address
 * @method $this byGivenName(string $name) Filter by given name (first name)
 * @method $this byFamilyName(string $name) Filter by family name (last name)
 * @method $this bySince(string $datetime) Filter by last updated since datetime
 * @method $this byUntil(string $datetime) Filter by last updated until datetime
 * @method $this orderById(string $direction = 'ASCENDING') Order by contact ID
 * @method $this orderByDateCreated(string $direction = 'ASCENDING') Order by creation date
 * @method $this orderByLastUpdated(string $direction = 'ASCENDING') Order by last updated date
 * @method $this orderByName(string $direction = 'ASCENDING') Order by name
 * @method $this orderByFirstName(string $direction = 'ASCENDING') Order by first name
 * @method $this orderByEmail(string $direction = 'ASCENDING') Order by email address
 */
class ContactQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new ContactFieldSelector();
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
        'since',
        'until',
    ];

    /**
     * Allowed orderBy fields for contacts endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'date_created',
        'last_updated',
        'name',
        'firstName',
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
        return $this->bySince($startDatetime)
            ->byUntil($endDatetime);
    }
}
