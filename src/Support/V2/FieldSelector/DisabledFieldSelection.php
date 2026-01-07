<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Trait for endpoints that do not support field selection
 *
 * Use this trait in FieldSelector classes for API endpoints that return
 * all fields by default and do not support field filtering.
 */
trait DisabledFieldSelection
{
    /**
     * Allowed fields for field selection
     *
     * Empty array as field selection is not supported for this endpoint.
     * All fields are returned by default.
     *
     * @var array<string>
     */
    protected array $allowedFields = [];
}
