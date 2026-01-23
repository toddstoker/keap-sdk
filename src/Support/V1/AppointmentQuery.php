<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use DateTimeInterface;
use Toddstoker\KeapSdk\Support\DateFormatter;
use Toddstoker\KeapSdk\Support\V1\FieldSelector\AppointmentFieldSelector;

/**
 * Query builder for Keap v1 Appointments API
 *
 * Provides appointment-specific filter validation and helpers for the
 * List Appointments endpoint via dynamic method calls.
 *
 * Note: These endpoints only work with Appointment objects stored in
 * Keap Max Classic. Appointments in Keap Pro or Keap Max are NOT accessible.
 *
 * @method $this byContactId(int $contactId) Filter by contact ID
 */
class AppointmentQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new AppointmentFieldSelector;
    }

    /**
     * Allowed filter fields for appointments endpoint
     *
     * Note: 'since' and 'until' are handled by dedicated methods
     * since they are date range filters, not simple field filters.
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'contact_id',
    ];

    /**
     * Allowed orderBy fields for appointments endpoint
     *
     * Note: The v1 appointments API does not document specific orderBy fields.
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [];

    /**
     * Start date for filtering appointments
     */
    protected ?string $since = null;

    /**
     * End date for filtering appointments
     */
    protected ?string $until = null;

    /**
     * Filter appointments from a specific date/time onwards
     *
     * @param  DateTimeInterface|string  $since  Date to start searching from (ISO 8601 format)
     * @return $this
     */
    public function since(DateTimeInterface|string $since): static
    {
        $this->since = $since instanceof DateTimeInterface
            ? DateFormatter::format($since)
            : $since;

        return $this;
    }

    /**
     * Filter appointments up to a specific date/time
     *
     * @param  DateTimeInterface|string  $until  Date to search to (ISO 8601 format)
     * @return $this
     */
    public function until(DateTimeInterface|string $until): static
    {
        $this->until = $until instanceof DateTimeInterface
            ? DateFormatter::format($until)
            : $until;

        return $this;
    }

    /**
     * Filter appointments within a date range
     *
     * @param  DateTimeInterface|string  $since  Start date
     * @param  DateTimeInterface|string  $until  End date
     * @return $this
     */
    public function between(DateTimeInterface|string $since, DateTimeInterface|string $until): static
    {
        return $this->since($since)->until($until);
    }

    /**
     * Convert the query to an array of query parameters
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = parent::toArray();

        // Add date range filters
        if ($this->since !== null) {
            $params['since'] = $this->since;
        }

        if ($this->until !== null) {
            $params['until'] = $this->until;
        }

        return $params;
    }
}
