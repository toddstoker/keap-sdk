<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Opportunity Stage responses
 *
 * Defines the allowed fields that can be included in
 * opportunity stage API responses.
 */
class OpportunityStageFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for field selection
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'checklist_items',
        'created_time',
        'id',
        'name',
        'order',
        'probability',
        'target_number_days',
        'updated_time',
    ];
}
