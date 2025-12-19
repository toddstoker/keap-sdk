<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use Toddstoker\KeapSdk\Support\V1\FieldSelector\OpportunityFieldSelector;

/**
 * Query builder for Keap v1 Opportunities API
 *
 * Provides opportunity-specific filter validation and helpers for the
 * List Opportunities endpoint via dynamic method calls.
 *
 * @method $this bySearchTerm(string $term) Filter by search term (searches contact and opportunity fields)
 * @method $this byStageId(int $stageId) Filter by opportunity stage ID
 * @method $this byUserId(int $userId) Filter by user ID
 * @method $this orderByNextAction(string $direction = 'ASCENDING') Order by next action date
 * @method $this orderByOpportunityName(string $direction = 'ASCENDING') Order by opportunity name
 * @method $this orderByContactName(string $direction = 'ASCENDING') Order by contact name
 * @method $this orderByDateCreated(string $direction = 'ASCENDING') Order by creation date
 */
class OpportunityQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new OpportunityFieldSelector();
    }

    /**
     * Allowed filter fields for opportunities endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'search_term',
        'stage_id',
        'user_id',
    ];

    /**
     * Allowed orderBy fields for opportunities endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'next_action',
        'opportunity_name',
        'contact_name',
        'date_created',
    ];
}
