<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

/**
 * Query builder for Keap v2 Users API
 *
 * Provides user-specific filter validation and helpers for the
 * List Users endpoint via dynamic method calls.
 *
 * @method $this byEmail(string $email) Filter by email address
 * @method $this byGivenName(string $name) Filter by given name (first name)
 * @method $this byIncludeInactive(bool $include) Filter to include inactive users
 * @method $this byIncludePartners(bool $include) Filter to include partner users
 * @method $this byUserIds(array $ids) Filter by specific user IDs
 * @method $this orderByDateCreated(string $direction = 'asc') Order by creation date
 * @method $this orderByEmail(string $direction = 'asc') Order by email address
 */
class UserQuery extends Query
{
    /**
     * Number of items per page (1-100)
     */
    protected int $pageSize = 100;

    /**
     * Allowed filter fields for users endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'email',
        'given_name',
        'include_inactive',
        'include_partners',
        'user_ids',
    ];

    /**
     * Allowed orderBy fields for users endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'date_created',
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
        'address',
        'admin',
        'company_name',
        'create_time',
        'created_by',
        'email_addresses',
        'family_name',
        'fax_numbers',
        'given_name',
        'global_user_id',
        'id',
        'keap_id',
        'partner',
        'phone_numbers',
        'social_accounts',
        'status',
        'title',
        'update_time',
        'updated_by',
        'website',
    ];
}
