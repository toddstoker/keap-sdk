<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use BadMethodCallException;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\FieldSelector;

/**
 * Base query builder for Keap v2 API list endpoints
 *
 * Provides a fluent interface for constructing queries with filters,
 * sorting, pagination, and field selection. Extend this class for
 * resource-specific query builders with validation and helper methods.
 *
 * Child classes should define:
 * - $allowedFilters: array of allowed filter field names
 * - $allowedOrderBy: array of allowed orderBy field names
 *
 * This enables dynamic method calls:
 * - by{FieldName}($value) - Adds a filter condition
 * - orderBy{FieldName}($direction) - Sets the orderBy clause
 */
abstract class Query
{
    protected FieldSelector $fieldSelector;

    /**
     * Filter conditions
     *
     * @var array<string>
     */
    protected array $filters = [];

    /**
     * Order by clause
     */
    protected ?string $orderBy = null;

    /**
     * Number of items per page (1-1000)
     */
    protected int $pageSize = 1000;

    /**
     * Page token for cursor-based pagination
     */
    protected ?string $pageToken = null;

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
     * Add a filter condition using equality operator
     *
     * @param  string  $field  Field name to filter on
     * @param  string  $value  Value to match
     * @return $this
     */
    public function where(string $field, string $value): static
    {
        $this->filters[] = "{$field}=={$value}";

        return $this;
    }

    /**
     * Add multiple filter conditions at once
     *
     * @param  array<string, string>  $conditions  Array of field => value pairs
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
     * @param  string  $direction  Sort direction ('asc' or 'desc')
     * @return $this
     */
    public function orderBy(string $field, string $direction = 'asc'): static
    {
        $this->orderBy = "{$field} {$direction}";

        return $this;
    }

    /**
     * Set the number of items to return per page
     *
     * @param  int  $size  Number of items (1-1000)
     * @return $this
     */
    public function pageSize(int $size): static
    {
        $this->pageSize = $size;

        return $this;
    }

    /**
     * Set the number of items to return per page (alias for pageSize)
     *
     * @param  int  $limit  Number of items (1-1000)
     * @return $this
     */
    public function limit(int $limit): static
    {
        return $this->pageSize($limit);
    }

    /**
     * Set the page token for cursor-based pagination
     *
     * @param  string  $token  Page token from previous response
     * @return $this
     */
    public function pageToken(string $token): static
    {
        $this->pageToken = $token;

        return $this;
    }

    /**
     * Set which fields to include in the response
     *
     * Proxies to FieldSelector.
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

    public function allFields(): static
    {
        $this->fieldSelector->allFields();

        return $this;
    }

    /**
     * Get the page token (if set)
     */
    public function getPageToken(): ?string
    {
        return $this->pageToken;
    }

    /**
     * Get the page size (if set)
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
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

            if (! in_array($field, $this->allowedFilters, true)) {
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

            // Handle array values (e.g., contact_ids)
            $value = is_array($args[0]) ? implode(',', $args[0]) : $args[0];

            return $this->where($field, $value);
        }

        // Pattern: orderBy{FieldName}($direction) -> orderBy('field_name', $direction)
        if (str_starts_with($method, 'orderBy')) {
            $field = $this->methodNameToFieldName($method, 'orderBy');

            if (! in_array($field, $this->allowedOrderBy, true)) {
                throw new BadMethodCallException(
                    "OrderBy field '{$field}' is not allowed. ".
                    'Allowed orderBy fields: '.implode(', ', $this->allowedOrderBy)
                );
            }

            $direction = $args[0] ?? 'asc';

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
     * - orderByCreateTime -> create_time
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

        if (! empty($this->filters)) {
            $params['filter'] = implode(';', $this->filters);
        }

        if ($this->orderBy !== null) {
            $params['order_by'] = $this->orderBy;
        }

        if ($this->pageSize !== null) {
            $params['page_size'] = $this->pageSize;
        }

        if ($this->pageToken !== null) {
            $params['page_token'] = $this->pageToken;
        }

        return array_merge($params, $this->fieldSelector->toArray());
    }
}
