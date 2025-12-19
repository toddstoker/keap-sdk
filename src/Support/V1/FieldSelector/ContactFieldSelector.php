<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1\FieldSelector;

class ContactFieldSelector extends FieldSelector
{
    /**
     * Allowed fields for field selection
     *
     * These are the optional properties that can be included in the response
     * via the fields() method (V1 API uses 'optional_properties' parameter).
     *
     * @var array<string>
     */
    protected array $allowedFields = [
        'addresses',
        'company',
        'contact_type',
        'custom_fields',
        'email_addresses',
        'family_name',
        'fax_numbers',
        'given_name',
        'id',
        'job_title',
        'middle_name',
        'origin',
        'owner_id',
        'phone_numbers',
        'preferred_locale',
        'preferred_name',
        'prefix',
        'social_accounts',
        'source_type',
        'spouse_name',
        'suffix',
        'tag_ids',
        'time_zone',
        'website',
    ];
}
