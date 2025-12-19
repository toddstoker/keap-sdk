<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1\FieldSelector;

class TagFieldSelector extends FieldSelector
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
        'name',
        'description',
        'category',
    ];
}
