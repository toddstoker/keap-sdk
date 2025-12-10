<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Data\Contact;
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Contacts\CreateContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\DeleteContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\ListContacts;
use Toddstoker\KeapSdk\Requests\V2\Contacts\UpdateContact;

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
class ContactsResource
{
    public function __construct(
        protected readonly Keap $connector
    ) {
    }

    /**
     * List contacts with optional filtering
     *
     * @param int $limit Maximum number of contacts to return
     * @param int $offset Number of contacts to skip
     * @param string|null $email Filter by email address
     * @param string|null $givenName Filter by first name
     * @param string|null $familyName Filter by last name
     * @param string|null $order Field to order results by
     * @return array{contacts: array<Contact>, count: int, next: ?string, previous: ?string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(
        int $limit = 100,
        int $offset = 0,
        ?string $email = null,
        ?string $givenName = null,
        ?string $familyName = null,
        ?string $order = null,
    ): array {
        $response = $this->connector->send(
            new ListContacts(
                limit: $limit,
                offset: $offset,
                email: $email,
                givenName: $givenName,
                familyName: $familyName,
                order: $order,
            )
        );

        $data = $response->json();

        return [
            'contacts' => array_map(
                fn (array $contactData) => Contact::fromArray($contactData),
                $data['contacts'] ?? []
            ),
            'count' => $data['count'] ?? 0,
            'next' => $data['next'] ?? null,
            'previous' => $data['previous'] ?? null,
        ];
    }

    /**
     * Get a specific contact by ID
     *
     * @param int $contactId The contact ID
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $contactId): Contact
    {
        $response = $this->connector->send(new GetContact($contactId));

        return Contact::fromArray($response->json());
    }

    /**
     * Create a new contact
     *
     * @param array<string, mixed> $data Contact data
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): Contact
    {
        $response = $this->connector->send(new CreateContact($data));

        return Contact::fromArray($response->json());
    }

    /**
     * Update an existing contact
     *
     * @param int $contactId The contact ID to update
     * @param array<string, mixed> $data Contact data to update
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(int $contactId, array $data): Contact
    {
        $response = $this->connector->send(
            new UpdateContact($contactId, $data)
        );

        return Contact::fromArray($response->json());
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
