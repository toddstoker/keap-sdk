<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Replace Appointment (v1)
 *
 * Replaces all values of a given appointment (full replacement via PUT).
 *
 * Required fields:
 * - title: The title of the appointment
 * - start_date: Start date/time in ISO 8601 format
 * - end_date: End date/time in ISO 8601 format
 *
 * Optional fields:
 * - contact_id: Required for pop-up reminders
 * - description: Description text
 * - location: Location of the appointment
 * - remind_time: Minutes before start_date for reminder (5, 10, 15, 30, 60, 120, 240, 480, 1440, 2880)
 * - user: Required only for pop-up reminders
 *
 * Note: Access Appointment objects stored in Keap Max Classic.
 * Appointment objects stored in Keap Pro or Keap Max are NOT available.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class ReplaceAppointment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  int  $appointmentId  The appointment ID to replace
     * @param  array<string, mixed>  $data  Complete appointment data
     */
    public function __construct(
        protected readonly int $appointmentId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/appointments/{$this->appointmentId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
