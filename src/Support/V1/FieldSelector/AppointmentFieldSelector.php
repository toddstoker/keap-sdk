<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1\FieldSelector;

/**
 * Field selector for v1 Appointments API
 *
 * Defines the available fields that can be requested for appointments
 * via the optional_properties parameter.
 */
class AppointmentFieldSelector extends FieldSelector
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
        'contact_id',
        'description',
        'end_date',
        'location',
        'remind_time',
        'start_date',
        'title',
        'user',
    ];
}
