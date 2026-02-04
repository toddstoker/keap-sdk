<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Affiliates
 *
 * The Affiliates API does not currently support field selection.
 * This class exists for consistency with other resources and future compatibility.
 */
class AffiliateFieldSelector extends FieldSelector
{
    use DisabledFieldSelection;
}
