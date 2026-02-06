<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\OpportunityStageFieldSelector;

/**
 * Query builder for Keap v2 Opportunity Stages API
 *
 * Provides opportunity stage-specific filter validation and helpers for the
 * List Opportunity Stages endpoint via dynamic method calls.
 *
 * @method $this orderByStageOrder(string $direction = 'asc') Order by stage order
 */
class OpportunityStageQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new OpportunityStageFieldSelector;
    }

    /**
     * Allowed filter fields for opportunity stages endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [];

    /**
     * Allowed orderBy fields for opportunity stages endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'stage_order',
    ];
}
