<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

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
     * Allowed fields for field selection
     *
     * These are the fields that can be included in the response
     * via the fields() method.
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'addresses',
        'anniversary_date',
        'birth_date',
        'company',
        'contact_type',
        'create_time',
        'custom_fields',
        'email_addresses',
        'family_name',
        'fax_numbers',
        'given_name',
        'id',
        'job_title',
        'leadsource_id',
        'links',
        'middle_name',
        'notes',
        'origin',
        'owner_id',
        'phone_numbers',
        'preferred_locale',
        'preferred_name',
        'prefix',
        'referral_code',
        'score_value',
        'social_accounts',
        'source_type',
        'spouse_name',
        'suffix',
        'tag_ids',
        'time_zone',
        'update_time',
        'utm_parameters',
        'website',
    ];

    /**
     * Convenience method: Filter by contacts updated between two dates
     *
     * @param string $startDatetime Start datetime (ISO 8601 format)
     * @param string $endDatetime End datetime (ISO 8601 format)
     * @return $this
     */
    public function updatedBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->byStartUpdateTime($startDatetime)
            ->byEndUpdateTime($endDatetime);
    }
}
