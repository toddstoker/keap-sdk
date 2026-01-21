<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Webforms endpoints
 *
 * The Webforms list endpoint does not support field selection,
 * so this class uses the DisabledFieldSelection trait.
 */
class WebformFieldSelector extends FieldSelector
{
    use DisabledFieldSelection;
}
