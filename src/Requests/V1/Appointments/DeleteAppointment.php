<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Appointment (v1)
 *
 * Deletes the specified appointment.
 *
 * Note: Access Appointment objects stored in Keap Max Classic.
 * Appointment objects stored in Keap Pro or Keap Max are NOT available.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class DeleteAppointment extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  int  $appointmentId  The appointment ID to delete
     */
    public function __construct(
        protected readonly int $appointmentId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/appointments/{$this->appointmentId}";
    }
}
