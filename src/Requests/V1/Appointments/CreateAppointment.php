<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Appointment (v1)
 *
 * Creates a new appointment as the authenticated user.
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
class CreateAppointment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data  Appointment data
     */
    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/appointments';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
