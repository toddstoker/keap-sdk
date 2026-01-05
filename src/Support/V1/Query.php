<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use BadMethodCallException;
use Toddstoker\KeapSdk\Support\V1\FieldSelector\FieldSelector;

/**
 * Base query builder for Keap v1 API list endpoints
 *
 * Provides a fluent interface for constructing queries with filters,
 * sorting, and offset-based pagination. Extend this class for
 * resource-specific query builders with validation and helper methods.
 *
 * Child classes should define:
 * - $allowedFilters: array of allowed filter field names
 * - $allowedOrderBy: array of allowed orderBy field names
 *
 * This enables dynamic method calls:
 * - by{FieldName}($value) - Adds a filter condition
 * - orderBy{FieldName}($direction) - Sets the orderBy clause
 *
 * @phpstan-consistent-constructor
 */
abstract class Query
{
    protected FieldSelector $fieldSelector;

    /**
     * Filter conditions (key-value pairs)
     *
     * @var array<string, mixed>
     */
    protected array $filters = [];

    /**
     * Order by field
     */
    protected ?string $order = null;

    /**
     * Order direction (ASCENDING or DESCENDING)
     */
    protected ?string $orderDirection = null;

    /**
     * Number of items to return (max 1000)
     */
    protected int $limit = 1000;

    /**
     * Number of items to skip
     */
    protected ?int $offset = null;

    /**
     * Allowed filter fields (defined by child classes)
     *
     * @var array<string>
     */
    protected array $allowedFilters = [];

    /**
     * Allowed orderBy fields (defined by child classes)
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [];

    /**
     * Create a new Query instance
     */
    public static function make(): static
    {
        return new static;
    }

    /**
     * Add a filter condition
     *
     * @param  string  $field  Field name to filter on
     * @param  mixed  $value  Value to match
     * @return $this
     */
    public function where(string $field, mixed $value): static
    {
        $this->filters[$field] = $value;

        return $this;
    }

    /**
     * Add multiple filter conditions at once
     *
     * @param  array<string, mixed>  $conditions  Array of field => value pairs
     * @return $this
     */
    public function whereMany(array $conditions): static
    {
        foreach ($conditions as $field => $value) {
            $this->where($field, $value);
        }

        return $this;
    }

    /**
     * Set the order by clause
     *
     * @param  string  $field  Field to order by
     * @param  string  $direction  Sort direction ('ASCENDING' or 'DESCENDING', default 'ASCENDING')
     * @return $this
     */
    public function orderBy(string $field, string $direction = 'ASCENDING'): static
    {
        // V1 uses 'ASCENDING' and 'DESCENDING' (all caps)
        // Accept lowercase and convert to uppercase for convenience
        $direction = strtoupper($direction);

        // Also accept 'asc' and 'desc' shorthand
        if ($direction === 'ASC') {
            $direction = 'ASCENDING';
        } elseif ($direction === 'DESC') {
            $direction = 'DESCENDING';
        }

        $this->order = $field;
        $this->orderDirection = $direction;

        return $this;
    }

    /**
     * Set the number of items to return
     *
     * @param  int  $limit  Number of items (1-1000)
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set the number of items to skip
     *
     * @param  int  $offset  Number of items to skip
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Set which fields to include in the response
     *
     * Proxies to FieldSelector.
     * V1 API uses 'optional_properties' parameter name.
     *
     * @param  array<string>  $fields  Array of field names
     * @return $this
     *
     * @throws \InvalidArgumentException If any field is not allowed
     */
    public function fields(array $fields): static
    {
        $this->fieldSelector->fields($fields);

        return $this;
    }

    /**
     * Select all available fields
     *
     * @return $this
     */
    public function allFields(): static
    {
        $this->fieldSelector->allFields();

        return $this;
    }

    /**
     * Get the limit (if set)
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Get the offset (if set)
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * Magic method to handle dynamic filter and orderBy methods
     *
     * Supports two patterns:
     * - by{FieldName}($value) - Adds a filter condition
     * - orderBy{FieldName}($direction) - Sets the orderBy clause
     *
     * Validates against $allowedFilters and $allowedOrderBy arrays
     * defined in child classes.
     *
     * @param  string  $method  Method name
     * @param  array<mixed>  $args  Method arguments
     * @return $this
     *
     * @throws BadMethodCallException If method pattern is invalid or field not allowed
     */
    public function __call(string $method, array $args): static
    {
        // Pattern: by{FieldName}($value) -> where('field_name', $value)
        if (str_starts_with($method, 'by')) {
            $field = $this->methodNameToFieldName($method, 'by');

            if (! empty($this->allowedFilters) && ! in_array($field, $this->allowedFilters, true)) {
                throw new BadMethodCallException(
                    "Filter field '{$field}' is not allowed. ".
                    'Allowed filters: '.implode(', ', $this->allowedFilters)
                );
            }

            if (! isset($args[0])) {
                throw new BadMethodCallException(
                    "Method {$method}() requires a value argument"
                );
            }

            return $this->where($field, $args[0]);
        }

        // Pattern: orderBy{FieldName}($direction) -> orderBy('field_name', $direction)
        if (str_starts_with($method, 'orderBy')) {
            $field = $this->methodNameToFieldName($method, 'orderBy');

            if (! empty($this->allowedOrderBy) && ! in_array($field, $this->allowedOrderBy, true)) {
                throw new BadMethodCallException(
                    "OrderBy field '{$field}' is not allowed. ".
                    'Allowed orderBy fields: '.implode(', ', $this->allowedOrderBy)
                );
            }

            $direction = $args[0] ?? 'ASCENDING';

            return $this->orderBy($field, $direction);
        }

        throw new BadMethodCallException(
            "Method {$method}() does not exist on ".static::class
        );
    }

    /**
     * Convert a camelCase method name to snake_case field name
     *
     * Examples:
     * - byEmail -> email
     * - byGivenName -> given_name
     * - orderByDateCreated -> date_created
     *
     * @param  string  $methodName  Method name (e.g., 'byGivenName')
     * @param  string  $prefix  Prefix to remove (e.g., 'by')
     * @return string Snake-case field name (e.g., 'given_name')
     */
    protected function methodNameToFieldName(string $methodName, string $prefix): string
    {
        // Remove prefix (by, orderBy)
        $fieldName = substr($methodName, strlen($prefix));

        // Convert PascalCase to snake_case
        $snakeCase = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $fieldName));

        return $snakeCase;
    }

    /**
     * Convert the query to an array of query parameters
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [];

        // Add all filters
        foreach ($this->filters as $field => $value) {
            $params[$field] = $value;
        }

        // Add order
        if ($this->order !== null) {
            $params['order'] = $this->order;
        }

        if ($this->orderDirection !== null) {
            $params['order_direction'] = $this->orderDirection;
        }

        // Add pagination
        if ($this->limit > 0) {
            $params['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $params['offset'] = $this->offset;
        }

        return array_merge($params, $this->fieldSelector->toArray());
    }
}
