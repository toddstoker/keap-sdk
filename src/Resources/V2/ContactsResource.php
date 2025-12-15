<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Contacts\CreateContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\DeleteContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\ListContacts;
use Toddstoker\KeapSdk\Requests\V2\Contacts\UpdateContact;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\ContactQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Contacts Resource (v2) - Recommended
 *
 * Provides methods for interacting with the Keap Contacts API v2.
 * V2 offers improved performance, better data structure consistency,
 * and enhanced filtering compared to v1.
 *
 * This is the default resource accessed when using the SDK without
 * specifying a version.
 *
 * @see https://developer.keap.com/docs/restv2/
 *
 */
readonly class ContactsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) { }

    /**
     * List contacts with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use paginate() to automatically
     * iterate through all pages.
     *
     * @param ContactQuery|null $query Query builder with filters and pagination options
     * @return array{contacts: array<array<string, mixed>>, next_page_token: ?string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException|\DateMalformedStringException
     */
    public function list(?ContactQuery $query = null): array
    {
        $query = $query ?? ContactQuery::make();

        $response = $this->connector->send(new ListContacts($query));
        $data = $response->json();

        return [
            'contacts' => $data['contacts'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through all contacts
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param ContactQuery|null $query Query builder with filters and pagination options
     * @return Paginator
     */
    public function paginate(?ContactQuery $query = null): Paginator
    {
        $query = $query ?? ContactQuery::make();

        return new Paginator(
            fn(ContactQuery $q) => $this->list($q),
            $query
        );
    }

    /**
     * Get a specific contact by ID
     *
     * @param int $contactId The contact ID
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $contactId): array
    {
        $response = $this->connector->send(new GetContact($contactId));

        return $response->json();
    }

    /**
     * Create a new contact
     *
     * @param array<string, mixed> $data Contact data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        $response = $this->connector->send(new CreateContact($data));

        return $response->json();
    }

    /**
     * Update an existing contact
     *
     * @param int $contactId The contact ID to update
     * @param array<string, mixed> $data Contact data to update
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(int $contactId, array $data): array
    {
        $response = $this->connector->send(
            new UpdateContact($contactId, $data)
        );

        return $response->json();
    }

    /**
     * Delete a contact
     *
     * @param int $contactId The contact ID to delete
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $contactId): bool
    {
        $response = $this->connector->send(new DeleteContact($contactId));

        return $response->successful();
    }
}
