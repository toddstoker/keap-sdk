<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

use Toddstoker\KeapSdk\Support\FieldSelector as BaseFieldSelector;

/**
 * Base field selector for v2 API endpoints
 *
 * V2 API uses 'fields' as the query parameter name.
 *
 * @phpstan-consistent-constructor
 */
abstract class FieldSelector extends BaseFieldSelector
{
    /**
     * Convert to query parameter array
     *
     * V2 uses 'fields' parameter.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->fields
            ? ['fields' => implode(',', $this->fields)]
            : [];
    }
}
