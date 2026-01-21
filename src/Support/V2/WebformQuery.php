<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\WebformFieldSelector;

/**
 * Query builder for Keap v2 Webforms API
 *
 * Provides webform-specific filter validation and helpers for the
 * List Webforms endpoint via dynamic method calls.
 *
 * @method $this byName(string $name) Filter by webform name
 * @method $this byWebformType(string $type) Filter by webform type (LEGACY, STANDALONE, FUNNEL, LANDING_PAGE, SURVEY, INTERNAL, TWITTER, UNKNOWN)
 * @method $this bySinceCreateTime(string $datetime) Filter by created since datetime
 * @method $this byUntilCreateTime(string $datetime) Filter by created until datetime
 * @method $this bySinceUpdateTime(string $datetime) Filter by updated since datetime
 * @method $this byUntilUpdateTime(string $datetime) Filter by updated until datetime
 * @method $this orderByName(string $direction = 'asc') Order by webform name
 * @method $this orderByWebformType(string $direction = 'asc') Order by webform type
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 * @method $this orderByUpdateTime(string $direction = 'asc') Order by update time
 */
class WebformQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new WebformFieldSelector;
    }

    /**
     * Allowed filter fields for webforms endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'name',
        'webform_type',
        'since_create_time',
        'until_create_time',
        'since_update_time',
        'until_update_time',
    ];

    /**
     * Allowed orderBy fields for webforms endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'name',
        'webform_type',
        'create_time',
        'update_time',
    ];

    /**
     * Convenience method: Filter by webforms created between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function createdBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceCreateTime($startDatetime)
            ->byUntilCreateTime($endDatetime);
    }

    /**
     * Convenience method: Filter by webforms updated between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function updatedBetween(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceUpdateTime($startDatetime)
            ->byUntilUpdateTime($endDatetime);
    }

    /**
     * Convenience method: Filter for legacy webforms
     *
     * @return $this
     */
    public function legacy(): static
    {
        return $this->byWebformType('legacy');
    }

    /**
     * Convenience method: Filter for standalone webforms
     *
     * @return $this
     */
    public function standalone(): static
    {
        return $this->byWebformType('standalone');
    }

    /**
     * Convenience method: Filter for funnel webforms
     *
     * @return $this
     */
    public function funnel(): static
    {
        return $this->byWebformType('funnel');
    }

    /**
     * Convenience method: Filter for landing page webforms
     *
     * @return $this
     */
    public function landingPage(): static
    {
        return $this->byWebformType('landing_page');
    }

    /**
     * Convenience method: Filter for survey webforms
     *
     * @return $this
     */
    public function survey(): static
    {
        return $this->byWebformType('survey');
    }
}
