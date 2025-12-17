<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

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
     * Allowed fields for field selection
     *
     * These are the optional properties that can be included in the response
     * via the fields() method (V1 API uses 'optional_properties' parameter).
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'addresses',
//        'anniversary', // Not in v1
//        'birthday', // Not in v1
        'company',
//        'company_name', // Not in v1
        'contact_type',
        'custom_fields',
//        'date_created', // Not in v1
        'email_addresses',
//        'email_opted_in', // Not in v1
//        'email_status', // Not in v1
        'family_name',
        'fax_numbers',
        'given_name',
        'id',
        'job_title',
//        'last_updated', // Not in v1
//        'lead_source_id', // Not in v1
        'middle_name',
//        'opt_in_reason', // Not in v1
        'origin',
        'owner_id',
        'phone_numbers',
        'preferred_locale',
        'preferred_name',
        'prefix',
//        'referral_code', // Not in v1
//        'relationships', // Not in v1
//        'score_value', // Not in v1
        'social_accounts',
        'source_type',
        'spouse_name',
        'suffix',
        'tag_ids',
        'time_zone',
//        'utm_parameters', // Not in v1
        'website',
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
