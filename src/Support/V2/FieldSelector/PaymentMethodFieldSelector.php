<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Payment Methods
 *
 * The Payment Methods API does not currently support field selection.
 * This class exists for consistency with other resources and future compatibility.
 */
class PaymentMethodFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for payment method field selection
     *
     * Note: The Payment Methods API does not currently document field selection support.
     * This array is empty but can be updated when field selection is added.
     *
     * @var array<string>
     */
    protected array $allowedFields = [];
}
