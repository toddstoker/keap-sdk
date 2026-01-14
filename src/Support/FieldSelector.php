<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

/**
 * Base field selector for API endpoints
 *
 * Provides field selection functionality for GET requests.
 * V1 and V2 APIs use different query parameter names, handled by
 * version-specific intermediate classes.
 *
 * @phpstan-consistent-constructor
 */
abstract class FieldSelector
{
    /**
     * Fields to include in response
     *
     * @var array<string>|null
     */
    protected ?array $fields = null;

    /**
     * Allowed fields for field selection (defined by child classes)
     *
     * @var array<string>
     */
    protected array $allowedFields = [];

    public static function make(): static
    {
        return new static;
    }

    /**
     * @param  FieldSelector|array<string>|string|null  $fields  Fields to select; can be an instance, array of field names, '*' for all fields, or null
     * @return static Resolved FieldSelector instance
     */
    public static function for(self|array|string|null $fields = null): static
    {
        if ($fields instanceof static) {
            return $fields;
        }

        $fieldSelector = static::make();

        if (is_array($fields)) {
            $fieldSelector->fields($fields);
        } elseif ($fields === '*') {
            $fieldSelector->allFields();
        }

        return $fieldSelector;
    }

    /**
     * Set which fields to include in the response
     *
     * Validates fields against the allowedFields array if defined.
     *
     * @param  array<string>  $fields  Array of field names
     * @return $this
     *
     * @throws \InvalidArgumentException If any field is not allowed
     */
    public function fields(array $fields): static
    {
        // Validate fields if allowedFields is defined
        if (! empty($this->allowedFields)) {
            $invalidFields = array_diff($fields, $this->allowedFields);

            if (! empty($invalidFields)) {
                throw new \InvalidArgumentException(
                    'Invalid field(s): '.implode(', ', $invalidFields).'. '.
                    'Allowed fields: '.implode(', ', $this->allowedFields)
                );
            }
        }

        $this->fields = $fields;

        return $this;
    }

    public function allFields(): static
    {
        $this->fields = $this->allowedFields;

        return $this;
    }

    /**
     * Convert to query parameter array
     *
     * Implemented by version-specific intermediate classes to use
     * the correct parameter name (V1: optional_properties, V2: fields).
     *
     * @return array<string, string>
     */
    abstract public function toArray(): array;
}
