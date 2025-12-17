<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

/**
 * Query builder for Keap v2 Reporting API - Run Report endpoint
 *
 * Provides a simplified query builder for running reports (Saved Searches).
 * Unlike other query builders, this doesn't use filter syntax but accepts
 * direct fields and order_by parameters.
 *
 * Note: The run report endpoint is deprecated as of v2 but still functional.
 */
class RunReportQuery
{
    /**
     * Order by clause
     */
    protected ?string $orderBy = null;

    /**
     * Number of items per page (1-1000)
     */
    protected ?int $pageSize = 1000;

    /**
     * Page token for cursor-based pagination
     */
    protected ?string $pageToken = null;

    /**
     * Fields to include in response
     *
     * @var array<string>|null
     */
    protected ?array $fields = null;

    /**
     * Create a new RunReportQuery instance
     */
    public static function make(): static
    {
        return new static;
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
     * @param  int  $size  Number of items (1-1000, default 1000)
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
     * @param  int  $limit  Number of items (1-1000, default 1000)
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
     * @param  array<string>  $fields  Array of field names (or empty for all fields)
     * @return $this
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

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
     * Convert the query to an array of query parameters
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [];

        if ($this->orderBy !== null) {
            $params['order_by'] = $this->orderBy;
        }

        if ($this->pageSize !== null) {
            $params['page_size'] = $this->pageSize;
        }

        if ($this->pageToken !== null) {
            $params['page_token'] = $this->pageToken;
        }

        if ($this->fields !== null && ! empty($this->fields)) {
            $params['fields'] = implode(',', $this->fields);
        }

        return $params;
    }
}
