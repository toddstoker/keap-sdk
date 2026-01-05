<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

class ContactFieldSelector extends FieldSelector
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
        'addresses',
        'anniversary_date',
        'birth_date',
        'company',
        'contact_type',
        'create_time',
        'custom_fields',
        'email_addresses',
        'family_name',
        'fax_numbers',
        'given_name',
        'id',
        'job_title',
        'leadsource_id',
        'links',
        'middle_name',
        'notes',
        'origin',
        'owner_id',
        'phone_numbers',
        'preferred_locale',
        'preferred_name',
        'prefix',
        'referral_code',
        'score_value',
        'social_accounts',
        'source_type',
        'spouse_name',
        'suffix',
        'tag_ids',
        'time_zone',
        'update_time',
        'utm_parameters',
        'website',
    ];
}
