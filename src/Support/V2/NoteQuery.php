<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\NoteFieldSelector;

/**
 * Query builder for Keap v2 Notes API
 *
 * Provides note-specific filter validation and helpers for the
 * List Notes endpoint via dynamic method calls.
 *
 * @method $this byAssignedToUserId(string $userId) Filter by assigned user ID
 * @method $this byTitle(string $title) Filter by note title
 * @method $this bySinceTime(string $datetime) Filter by notes created/updated since datetime
 * @method $this byUntilTime(string $datetime) Filter by notes created/updated until datetime
 * @method $this orderById(string $direction = 'asc') Order by note ID
 * @method $this orderByCreateTime(string $direction = 'asc') Order by creation time
 */
class NoteQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new NoteFieldSelector;
    }

    /**
     * Allowed filter fields for notes endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'assigned_to_user_id',
        'title',
        'since_time',
        'until_time',
    ];

    /**
     * Allowed orderBy fields for notes endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'id',
        'create_time',
    ];

    /**
     * Convenience method: Filter by notes created/updated between two dates
     *
     * @param  string  $startDatetime  Start datetime (ISO 8601 format)
     * @param  string  $endDatetime  End datetime (ISO 8601 format)
     * @return $this
     */
    public function between(string $startDatetime, string $endDatetime): static
    {
        return $this->bySinceTime($startDatetime)
            ->byUntilTime($endDatetime);
    }
}
