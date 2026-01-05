<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Tag Contacts endpoint
 *
 * Note: The List Contacts with Tag endpoint does not support field selection.
 * This class exists to satisfy the Query base class requirements.
 */
class TagContactFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for field selection
     *
     * Empty array as this endpoint does not support field selection.
     *
     * @var array<string>
     */
    protected array $allowedFields = [];
}
