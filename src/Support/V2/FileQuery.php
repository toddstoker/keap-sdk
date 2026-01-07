<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\FileFieldSelector;

/**
 * Query builder for Keap v2 Files API
 *
 * Provides file-specific filter validation and helpers for the
 * List Files endpoint via dynamic method calls.
 *
 * @method $this byIsPublic(bool $isPublic) Filter by public status
 * @method $this byContactId(int $contactId) Filter by contact ID
 * @method $this byUserId(int $userId) Filter by user ID
 * @method $this byCategory(string $category) Filter by file category
 * @method $this byFileBoxType(string $fileBoxType) Filter by file box type
 * @method $this orderByFileName(string $direction = 'asc') Order by file name
 * @method $this orderByUpdatedTime(string $direction = 'asc') Order by updated time
 */
class FileQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new FileFieldSelector;
    }

    /**
     * Allowed filter fields for files endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'is_public',
        'contact_id',
        'user_id',
        'category',
        'file_box_type',
    ];

    /**
     * Allowed orderBy fields for files endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'file_name',
        'updated_time',
    ];
}
