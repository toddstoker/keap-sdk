<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Appointments\CreateAppointment;
use Toddstoker\KeapSdk\Requests\V1\Appointments\CreateAppointmentCustomField;
use Toddstoker\KeapSdk\Requests\V1\Appointments\DeleteAppointment;
use Toddstoker\KeapSdk\Requests\V1\Appointments\GetAppointment;
use Toddstoker\KeapSdk\Requests\V1\Appointments\GetAppointmentModel;
use Toddstoker\KeapSdk\Requests\V1\Appointments\ListAppointments;
use Toddstoker\KeapSdk\Requests\V1\Appointments\ReplaceAppointment;
use Toddstoker\KeapSdk\Requests\V1\Appointments\UpdateAppointment;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V1\AppointmentQuery;
use Toddstoker\KeapSdk\Support\V1\Paginator;

/**
 * Appointments Resource (v1)
 *
 * Provides methods for interacting with the Keap Appointments API v1.
 * This resource is accessed via the Keap connector's magic __call() method.
 *
 * Note: Access Appointment objects stored in Keap Max Classic.
 * Appointment objects stored in Keap Pro or Keap Max are NOT available via these methods.
 *
 * @see https://developer.infusionsoft.com/docs/rest/#tag/Appointment
 */
readonly class AppointmentsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List appointments with filtering and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  AppointmentQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     appointments: array<int, array{
     *         contact_id?: int,
     *         description?: string,
     *         end_date: string,
     *         location?: string,
     *         remind_time?: int,
     *         start_date: string,
     *         title: string,
     *         user?: int
     *     }>,
     *     count: int,
     *     next: ?string,
     *     previous: ?string,
     *     sync_token?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?AppointmentQuery $query = null): array
    {
        $query = $query ?? AppointmentQuery::make();

        $response = $this->connector->send(new ListAppointments($query));
        $data = $response->json();

        return [
            'appointments' => $data['appointments'] ?? [],
            'count' => $data['count'] ?? 0,
            'next' => $data['next'] ?? null,
            'previous' => $data['previous'] ?? null,
            'sync_token' => $data['sync_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list appointments endpoint.
     *
     * Automatically fetches subsequent pages using offset-based pagination.
     *
     * @param  AppointmentQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(?AppointmentQuery $query = null): Paginator
    {
        $query = $query ?? AppointmentQuery::make();

        return new Paginator(
            fn (AppointmentQuery $q) => $this->list($q),
            $query,
            'appointments'
        );
    }

    /**
     * Get a specific appointment by ID
     *
     * @param  int  $appointmentId  The appointment ID
     * @return array{
     *     contact_id?: int,
     *     description?: string,
     *     end_date: string,
     *     location?: string,
     *     remind_time?: int,
     *     start_date: string,
     *     title: string,
     *     user?: int
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $appointmentId): array
    {
        $response = $this->connector->send(new GetAppointment($appointmentId));

        return $response->json();
    }

    /**
     * Create a new appointment
     *
     * Required fields:
     * - title: The title of the appointment
     * - start_date: Start date/time in ISO 8601 format
     * - end_date: End date/time in ISO 8601 format
     *
     * @param  array{
     *     title: string,
     *     start_date: string,
     *     end_date: string,
     *     contact_id?: int,
     *     description?: string,
     *     location?: string,
     *     remind_time?: int,
     *     user?: int
     * }  $data  Appointment data
     * @return array{
     *     contact_id?: int,
     *     description?: string,
     *     end_date: string,
     *     location?: string,
     *     remind_time?: int,
     *     start_date: string,
     *     title: string,
     *     user?: int
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        $response = $this->connector->send(new CreateAppointment($data));

        return $response->json();
    }

    /**
     * Replace an existing appointment (full replacement)
     *
     * Replaces all values of a given appointment. All required fields must be provided.
     *
     * @param  int  $appointmentId  The appointment ID to replace
     * @param  array{
     *     title: string,
     *     start_date: string,
     *     end_date: string,
     *     contact_id?: int,
     *     description?: string,
     *     location?: string,
     *     remind_time?: int,
     *     user?: int
     * }  $data  Complete appointment data
     * @return array{
     *     contact_id?: int,
     *     description?: string,
     *     end_date: string,
     *     location?: string,
     *     remind_time?: int,
     *     start_date: string,
     *     title: string,
     *     user?: int
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function replace(int $appointmentId, array $data): array
    {
        $response = $this->connector->send(
            new ReplaceAppointment($appointmentId, $data)
        );

        return $response->json();
    }

    /**
     * Update an existing appointment (partial update)
     *
     * Updates only the provided values of a given appointment.
     *
     * @param  int  $appointmentId  The appointment ID to update
     * @param  array{
     *     title?: string,
     *     start_date?: string,
     *     end_date?: string,
     *     contact_id?: int,
     *     description?: string,
     *     location?: string,
     *     remind_time?: int,
     *     user?: int
     * }  $data  Appointment data to update
     * @return array{
     *     contact_id?: int,
     *     description?: string,
     *     end_date: string,
     *     location?: string,
     *     remind_time?: int,
     *     start_date: string,
     *     title: string,
     *     user?: int
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(int $appointmentId, array $data): array
    {
        $response = $this->connector->send(
            new UpdateAppointment($appointmentId, $data)
        );

        return $response->json();
    }

    /**
     * Delete an appointment
     *
     * @param  int  $appointmentId  The appointment ID to delete
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $appointmentId): bool
    {
        $response = $this->connector->send(new DeleteAppointment($appointmentId));

        return $response->successful();
    }

    /**
     * Get appointment model
     *
     * Retrieves the custom fields for the Appointment object.
     *
     * @return array{
     *     custom_fields?: array<int, array{
     *         id: int,
     *         field_name: string,
     *         field_type: string,
     *         label: string,
     *         default_value?: string,
     *         options?: array<int, array{id: string, label: string}>,
     *         record_type: string
     *     }>,
     *     optional_properties?: array<string>
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getModel(): array
    {
        return $this->connector->send(new GetAppointmentModel)->json();
    }

    /**
     * Create a custom field for appointments
     *
     * Adds a custom field of the specified type and options to the Appointment object.
     *
     * @param  array{
     *     label: string,
     *     field_type: string,
     *     group_id?: int,
     *     options?: array<int, array{label: string, options?: array<mixed>}>,
     *     user_group_id?: int
     * }  $data  Custom field data
     *
     * @phpstan-param array<string, mixed> $data
     *
     * @return array{
     *     id: int,
     *     field_name: string,
     *     field_type: string,
     *     label: string,
     *     default_value?: string,
     *     options?: array<int, array{id: string, label: string}>,
     *     record_type: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createCustomField(array $data): array
    {
        return $this->connector->send(new CreateAppointmentCustomField($data))->json();
    }
}
