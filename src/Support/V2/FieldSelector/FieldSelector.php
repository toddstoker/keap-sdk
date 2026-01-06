<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Base field selector for v2 API endpoints
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
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->fields
            ? ['fields' => implode(',', $this->fields)]
            : [];
    }
}
