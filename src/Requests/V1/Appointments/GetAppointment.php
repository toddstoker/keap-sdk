<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Appointment (v1)
 *
 * Retrieves a specific appointment with respect to user permissions.
 * The authenticated user will need the "can view all records" permission
 * for Task/Appt/Notes.
 *
 * Note: Access Appointment objects stored in Keap Max Classic.
 * Appointment objects stored in Keap Pro or Keap Max are NOT available.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class GetAppointment extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $appointmentId  The appointment ID
     */
    public function __construct(
        protected readonly int $appointmentId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/appointments/{$this->appointmentId}";
    }
}
