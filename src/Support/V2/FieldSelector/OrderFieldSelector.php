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
    use DisabledFieldSelection;
}
