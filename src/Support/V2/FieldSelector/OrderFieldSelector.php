<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Orders
 *
 * The Orders API does not currently support field selection.
 * This class exists for consistency with other resources and future compatibility.
 */
class OrderFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for order field selection
     *
     * Note: The Orders API does not currently document field selection support.
     * This array is empty but can be updated when field selection is added.
     *
     * @var array<string>
     */
    protected array $allowedFields = [];
}
