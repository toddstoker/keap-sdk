<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Tag Companies endpoint
 *
 * Note: The List Companies with Tag endpoint does not support field selection.
 * This class exists to satisfy the Query base class requirements.
 */
class TagCompanyFieldSelector extends FieldSelector
{
    use DisabledFieldSelection;
}
