<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1\FieldSelector;

use Toddstoker\KeapSdk\Support\FieldSelector as BaseFieldSelector;

/**
 * Base field selector for v1 API endpoints
 *
 * V1 API uses 'optional_properties' as the query parameter name.
 *
 * @phpstan-consistent-constructor
 */
abstract class FieldSelector extends BaseFieldSelector
{
    /**
     * Convert to query parameter array
     *
     * V1 uses 'optional_properties' parameter.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->fields
            ? ['optional_properties' => implode(',', $this->fields)]
            : [];
    }
}
