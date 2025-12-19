<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1\FieldSelector;

class OpportunityFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for field selection
     *
     * These are the fields that can be included in the response
     * via the fields() method.
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'id',
        'opportunity_title',
        'contact',
        'stage',
        'user',
        'date_created',
        'last_updated',
        'next_action_date',
        'next_action_notes',
        'opportunity_notes',
        'projected_revenue_low',
        'projected_revenue_high',
        'estimated_close_date',
        'include_in_forecast',
        'affiliate_id',
        'custom_fields',
    ];
}
