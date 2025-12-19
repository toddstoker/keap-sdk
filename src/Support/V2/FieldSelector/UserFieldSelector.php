<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

class UserFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for field selection
     *
     * These are the fields that can be included in the response
     * via the fields() method.
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'address',
        'admin',
        'company_name',
        'create_time',
        'created_by',
        'email_addresses',
        'family_name',
        'fax_numbers',
        'given_name',
        'global_user_id',
        'id',
        'keap_id',
        'partner',
        'phone_numbers',
        'social_accounts',
        'status',
        'title',
        'update_time',
        'updated_by',
        'website',
    ];
}
