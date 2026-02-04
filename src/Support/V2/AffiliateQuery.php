<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\AffiliateFieldSelector;

/**
 * Query builder for Keap v2 Affiliates API
 *
 * Provides affiliate-specific filter validation and helpers for the
 * List Affiliates endpoint via dynamic method calls.
 *
 * @method $this byAffiliateName(string $name) Filter by affiliate name
 * @method $this byContactId(string $contactId) Filter by contact ID
 * @method $this byStatus(string $status) Filter by status (ACTIVE or INACTIVE)
 * @method $this byCode(string $code) Filter by affiliate code
 * @method $this orderById(string $direction = 'asc') Order by ID
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByName(string $direction = 'asc') Order by name
 * @method $this orderByStatus(string $direction = 'asc') Order by status
 * @method $this orderByCode(string $direction = 'asc') Order by code
 */
class AffiliateQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new AffiliateFieldSelector;
    }

    /**
     * Allowed filter fields for affiliates endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'affiliate_name',
        'contact_id',
        'status',
        'code',
    ];

    /**
     * Allowed orderBy fields for affiliates endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'create_time',
        'name',
        'status',
        'code',
    ];
}
