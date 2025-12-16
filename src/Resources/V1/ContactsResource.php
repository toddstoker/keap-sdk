<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Contacts\AddUtmToContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ApplyTagToContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\CreateContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\CreateContactEmail;
use Toddstoker\KeapSdk\Requests\V1\Contacts\CreateCreditCard;
use Toddstoker\KeapSdk\Requests\V1\Contacts\CreateCustomField;
use Toddstoker\KeapSdk\Requests\V1\Contacts\DeleteContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContactEmails;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContactModel;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetContactTags;
use Toddstoker\KeapSdk\Requests\V1\Contacts\GetCreditCards;
use Toddstoker\KeapSdk\Requests\V1\Contacts\ListContacts;
use Toddstoker\KeapSdk\Requests\V1\Contacts\RemoveAllTagsFromContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\RemoveTagFromContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\UpdateOrCreateContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\UpdateContact;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V1\ContactQuery;
use Toddstoker\KeapSdk\Support\V1\Paginator;

/**
 * Contacts Resource (v1)
 *
 * Provides methods for interacting with the Keap Contacts API v1.
 * This resource is accessed via the Keap connector's magic __call() method.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 *
 */
readonly class ContactsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {
    }

    /**
     * List contacts with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param ContactQuery|null $query Query builder with filters and pagination options
     * @return array{contacts: array<array<string, mixed>>, count: int, next: ?string, previous: ?string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException|\DateMalformedStringException
     */
    public function list(?ContactQuery $query = null): array
    {
        $query = $query ?? ContactQuery::make()->limit(100);

        $response = $this->connector->send(new ListContacts($query));
        $data = $response->json();

        return [
            'contacts' => $data['contacts'] ?? [],
            'count' => $data['count'] ?? 0,
            'next' => $data['next'] ?? null,
            'previous' => $data['previous'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list contacts endpoint.
     *
     * Automatically fetches subsequent pages using offset-based pagination.
     *
     * @param ContactQuery|null $query Query builder with filters and pagination options
     * @return Paginator
     */
    public function newListPaginator(?ContactQuery $query = null): Paginator
    {
        $query = $query ?? ContactQuery::make()->limit(100);

        return new Paginator(
            fn(ContactQuery $q) => $this->list($q),
            $query
        );
    }

    /**
     * Get a specific contact by ID
     *
     * @param int $contactId The contact ID
     * @param array<string>|null $optionalProperties Optional properties to include
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $contactId, ?array $optionalProperties = null): array
    {
        $response = $this->connector->send(
            new GetContact($contactId, $optionalProperties)
        );

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

    /**
     * Get contact model
     *
     * Retrieves the custom fields for the Contact object.
     *
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getModel(): array
    {
        return $this->connector->send(new GetContactModel())->json();
    }

    /**
     * Get contact tags
     *
     * Retrieves a list of tags applied to a contact.
     *
     * @param int $contactId The contact ID
     * @param int|null $limit Max number of results
     * @param int|null $offset Starting offset
     * @return array{tags: array<array<string, mixed>>, count: int}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getTags(int $contactId, ?int $limit = null, ?int $offset = null): array
    {
        return $this->connector->send(new GetContactTags($contactId, $limit, $offset))->json();
    }

    /**
     * Get contact emails
     *
     * Retrieves a list of emails sent to a contact.
     *
     * @param int $contactId The contact ID
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getEmails(int $contactId): array
    {
        return $this->connector->send(new GetContactEmails($contactId))->json();
    }

    /**
     * Update or create contact
     *
     * Updates or creates a contact.
     *
     * @param array<string, mixed> $data Contact data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function updateOrCreate(array $data): array
    {
        return $this->connector->send(new UpdateOrCreateContact($data))->json();
    }

    /**
     * Create custom field
     *
     * Creates a new custom field for contacts.
     *
     * @param array<string, mixed> $data Custom field data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createCustomField(array $data): array
    {
        return $this->connector->send(new CreateCustomField($data))->json();
    }

    /**
     * Get credit cards
     *
     * Retrieves all credit cards for a contact.
     *
     * @param int $contactId The contact ID
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getCreditCards(int $contactId): array
    {
        return $this->connector->send(new GetCreditCards($contactId))->json();
    }

    /**
     * Create credit card
     *
     * Creates a credit card for a contact.
     *
     * @param int $contactId The contact ID
     * @param array<string, mixed> $data Credit card data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createCreditCard(int $contactId, array $data): array
    {
        return $this->connector->send(new CreateCreditCard($contactId, $data))->json();
    }

    /**
     * Create contact email
     *
     * Creates a record of an email sent to a contact.
     *
     * @param int $contactId The contact ID
     * @param array<string, mixed> $data Email data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createEmail(int $contactId, array $data): array
    {
        return $this->connector->send(new CreateContactEmail($contactId, $data))->json();
    }

    /**
     * Remove all tags from contact
     *
     * Removes all tags from a contact.
     *
     * @param int $contactId The contact ID
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeAllTags(int $contactId): bool
    {
        return $this->connector->send(new RemoveAllTagsFromContact($contactId))->successful();
    }

    /**
     * Add UTM to contact
     *
     * Adds UTM parameters to a contact.
     *
     * @param int $contactId The contact ID
     * @param array<string, mixed> $data UTM data
     * @return array<string, mixed>
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function addUtm(int $contactId, array $data): array
    {
        return $this->connector->send(new AddUtmToContact($contactId, $data))->json();
    }
}
