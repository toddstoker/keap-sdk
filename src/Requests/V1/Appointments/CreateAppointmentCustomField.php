<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Appointment Custom Field (v1)
 *
 * Adds a custom field of the specified type and options to the Appointment object.
 *
 * Required fields:
 * - label: Display label for the custom field
 * - field_type: Type of field (Currency, Date, DateTime, DayOfWeek, Drilldown, Email,
 *   Month, ListBox, Name, WholeNumber, DecimalNumber, Percent, PhoneNumber, Radio,
 *   Dropdown, SocialSecurityNumber, State, Text, TextArea, User, UserListBox, Website,
 *   Year, YesNo)
 *
 * Optional fields:
 * - group_id: Tab group to place the field under (defaults to 'Custom Fields' tab)
 * - options: Options for dropdown/select fields
 * - user_group_id: User group for User or UserListBox fields
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class CreateAppointmentCustomField extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data  Custom field data
     */
    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/appointments/model/customFields';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
