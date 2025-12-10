<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Contacts
 *
 * Retrieves a list of all contacts with optional filtering and pagination.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListContacts extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $limit Sets a total of items to return (max 1000)
     * @param int $offset Sets a beginning range of items to return
     * @param string|null $email Optional email to query on
     * @param string|null $givenName Optional first name to query on
     * @param string|null $familyName Optional last name to query on
     * @param string|null $order Attribute to order items by
     * @param string|null $orderDirection How to order the data (ASCENDING or DESCENDING)
     * @param string|null $since Date to start searching from (ISO 8601 format)
     * @param string|null $until Date to search to (ISO 8601 format)
     */
    public function __construct(
        protected readonly int $limit = 100,
        protected readonly int $offset = 0,
        protected readonly ?string $email = null,
        protected readonly ?string $givenName = null,
        protected readonly ?string $familyName = null,
        protected readonly ?string $order = null,
        protected readonly ?string $orderDirection = null,
        protected readonly ?string $since = null,
        protected readonly ?string $until = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contacts';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return array_filter([
            'limit' => $this->limit,
            'offset' => $this->offset,
            'email' => $this->email,
            'given_name' => $this->givenName,
            'family_name' => $this->familyName,
            'order' => $this->order,
            'order_direction' => $this->orderDirection,
            'since' => $this->since,
            'until' => $this->until,
        ], fn ($value) => $value !== null);
    }
}
