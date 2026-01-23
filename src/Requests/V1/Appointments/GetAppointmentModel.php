<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Appointment Model (v1)
 *
 * Retrieves the custom fields for the Appointment object.
 *
 * Note: Appointment custom fields use the record type 'TASK_NOTE_APPOINTMENT'
 * which is shared with Tasks and Notes.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class GetAppointmentModel extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/appointments/model';
    }
}
