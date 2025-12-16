<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

/**
 * Query builder for Keap v2 Tags API
 *
 * Provides tag-specific filter validation and helpers for the
 * List Tags endpoint via dynamic method calls.
 *
 * @method $this byName(string $name) Filter by tag name
 * @method $this byDescription(string $description) Filter by description (pass "NONE" for tags without description)
 * @method $this byCategoryId(string $categoryId) Filter by category ID (pass "NONE" for uncategorized tags)
 * @method $this bySinceCreateTime(string $datetime) Filter by created since datetime
 * @method $this byUntilCreateTime(string $datetime) Filter by created until datetime
 * @method $this bySinceUpdateTime(string $datetime) Filter by updated since datetime
 * @method $this byUntilUpdateTime(string $datetime) Filter by updated until datetime
 * @method $this orderByName(string $direction = 'asc') Order by tag name
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByUpdateTime(string $direction = 'asc') Order by update time
 */
class TagQuery extends Query
{
    /**
     * Allowed filter fields for tags endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'name',
        'description',
        'category_id',
        'since_create_time',
        'until_create_time',
        'since_update_time',
        'until_update_time',
    ];

    /**
     * Allowed orderBy fields for tags endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'name',
        'create_time',
        'update_time',
    ];

    /**
     * Convenience method: Filter by tags created between two dates
     *
     * @param string $startDatetime Start datetime (ISO 8601 format)
     * @param string $endDatetime End datetime (ISO 8601 format)
     * @return $this
     */
    public function createdBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceCreateTime($startDatetime)
            ->byUntilCreateTime($endDatetime);
    }

    /**
     * Convenience method: Filter by tags updated between two dates
     *
     * @param string $startDatetime Start datetime (ISO 8601 format)
     * @param string $endDatetime End datetime (ISO 8601 format)
     * @return $this
     */
    public function updatedBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceUpdateTime($startDatetime)
            ->byUntilUpdateTime($endDatetime);
    }

    /**
     * Convenience method: Filter for tags without a category
     *
     * @return $this
     */
    public function withoutCategory(): static
    {
        return $this->byCategoryId('NONE');
    }

    /**
     * Convenience method: Filter for tags without a description
     *
     * @return $this
     */
    public function withoutDescription(): static
    {
        return $this->byDescription('NONE');
    }
}
