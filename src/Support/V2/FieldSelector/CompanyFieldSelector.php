<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2\FieldSelector;

/**
 * Field selector for Companies endpoint (v2)
 *
 * Available fields for the GET /v2/companies/{company_id} endpoint:
 * - company_name, address, custom_fields, email_address,
 *   fax_number, phone_number, website, notes
 *
 * For List endpoint, additional fields available:
 * - notes, fax_number, email_address, phone_number,
 *   update_time, create_time, custom_fields
 */
class CompanyFieldSelector extends FieldSelector
{
    /**
     * @var array<string>
     */
    protected array $allowedFields = [
        'company_name',
        'address',
        'custom_fields',
        'email_address',
        'fax_number',
        'phone_number',
        'website',
        'notes',
        'create_time',
        'update_time',
    ];
}
