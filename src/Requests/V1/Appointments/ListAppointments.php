<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Appointments;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Toddstoker\KeapSdk\Support\V1\AppointmentQuery;

/**
 * List Appointments (v1)
 *
 * Retrieves all appointments for the authenticated user with filtering and pagination.
 *
 * Note: Access Appointment objects stored in Keap Max Classic.
 * Appointment objects stored in Keap Pro or Keap Max are NOT available.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
class ListAppointments extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  AppointmentQuery  $queryBuilder  The query builder with filters and pagination
     */
    public function __construct(
        protected readonly AppointmentQuery $queryBuilder
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/appointments';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryBuilder->toArray();
    }
}
