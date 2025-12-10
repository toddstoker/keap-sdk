<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Data\Contact;
use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ApplyTagToContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\CreateContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\DeleteContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ListContacts;
use Toddstoker\KeapSdk\Requests\V1\Contacts\RemoveTagFromContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\UpdateContact;

/**
 * Contacts Resource (v1)
 *
 * Provides methods for interacting with the Keap Contacts API v1.
 * This resource is accessed via the Keap connector's magic __call() method.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
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
     * @param int $limit Maximum number of contacts to return (max 1000)
     * @param int $offset Number of contacts to skip
     * @param string|null $email Filter by email address
     * @param string|null $givenName Filter by first name
     * @param string|null $familyName Filter by last name
     * @param string|null $order Field to order results by
     * @param string|null $orderDirection Sort direction (ASCENDING or DESCENDING)
     * @param string|null $since Filter contacts created/updated since this date
     * @param string|null $until Filter contacts created/updated until this date
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
        ?string $orderDirection = null,
        ?string $since = null,
        ?string $until = null,
    ): array {
        $response = $this->connector->send(
            new ListContacts(
                limit: $limit,
                offset: $offset,
                email: $email,
                givenName: $givenName,
                familyName: $familyName,
                order: $order,
                orderDirection: $orderDirection,
                since: $since,
                until: $until,
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
     * @param array<string>|null $optionalProperties Optional properties to include
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $contactId, ?array $optionalProperties = null): Contact
    {
        $response = $this->connector->send(
            new GetContact($contactId, $optionalProperties)
        );

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

    /**
     * Apply a tag to a contact
     *
     * @param int $contactId The contact ID
     * @param int $tagId The tag ID to apply
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function applyTag(int $contactId, int $tagId): bool
    {
        $response = $this->connector->send(
            new ApplyTagToContact($contactId, $tagId)
        );

        return $response->successful();
    }

    /**
     * Remove a tag from a contact
     *
     * @param int $contactId The contact ID
     * @param int $tagId The tag ID to remove
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeTag(int $contactId, int $tagId): bool
    {
        $response = $this->connector->send(
            new RemoveTagFromContact($contactId, $tagId)
        );

        return $response->successful();
    }
}
