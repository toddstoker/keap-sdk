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
use Toddstoker\KeapSdk\Requests\V1\Contacts\RemoveTagsFromContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\RemoveTagFromContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\UpdateContact;
use Toddstoker\KeapSdk\Requests\V1\Contacts\UpdateOrCreateContact;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V1\ContactQuery;
use Toddstoker\KeapSdk\Support\V1\FieldSelector\ContactFieldSelector;
use Toddstoker\KeapSdk\Support\V1\Paginator;

/**
 * Contacts Resource (v1)
 *
 * Provides methods for interacting with the Keap Contacts API v1.
 * This resource is accessed via the Keap connector's magic __call() method.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class ContactsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List contacts with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  ContactQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     contacts: array<int, array{
     *         id: int,
     *         given_name?: string,
     *         family_name?: string,
     *         email_addresses?: array<int, array{email: string, field: string}>,
     *         phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *         company?: array{id: int, company_name?: string},
     *         owner_id?: int,
     *         date_created?: string,
     *         last_updated?: string,
     *         email_status?: string,
     *         email_opted_in?: bool,
     *         ...
     *     }>,
     *     count: int,
     *     next: ?string,
     *     previous: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?ContactQuery $query = null): array
    {
        $query = $query ?? ContactQuery::make();

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
     * @param  ContactQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(?ContactQuery $query = null): Paginator
    {
        $query = $query ?? ContactQuery::make();

        return new Paginator(
            fn (ContactQuery $q) => $this->list($q),
            $query,
            'contacts'
        );
    }

    /**
     * Get a specific contact by ID
     *
     * Supports optional field selection. Pass an array of field names,
     * a ContactFieldSelector instance, or null to get default fields.
     *
     * @param  int  $contactId  The contact ID
     * @param  ContactFieldSelector|array<string>|null  $fields  Fields to include in response
     * @return array{
     *     id: int,
     *     given_name?: string,
     *     family_name?: string,
     *     middle_name?: string,
     *     preferred_name?: string,
     *     prefix?: string,
     *     suffix?: string,
     *     email_addresses?: array<int, array{email: string, field: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string, extension?: string}>,
     *     fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     addresses?: array<int, array{
     *         country_code?: string,
     *         line1?: string,
     *         line2?: string,
     *         locality?: string,
     *         postal_code?: string,
     *         region?: string,
     *         zip_code?: string,
     *         zip_four?: string,
     *         field: string
     *     }>,
     *     company?: array{id: int, company_name?: string},
     *     company_name?: string,
     *     job_title?: string,
     *     website?: string,
     *     birthday?: string,
     *     anniversary?: string,
     *     spouse_name?: string,
     *     time_zone?: string,
     *     preferred_locale?: string,
     *     tag_ids?: array<int>,
     *     date_created?: string,
     *     last_updated?: string,
     *     owner_id?: int,
     *     lead_source_id?: int,
     *     opt_in_reason?: string,
     *     email_status?: string,
     *     email_opted_in?: bool,
     *     source_type?: string,
     *     contact_type?: string,
     *     ScoreValue?: string,
     *     custom_fields?: array<int, array{id: int, content: mixed}>,
     *     origin?: array{date: string, ip_address: string},
     *     social_accounts?: array,
     *     relationships?: array,
     *     ...
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $contactId, ContactFieldSelector|array|null $fields = null): array
    {
        // Convert array to ContactFieldSelector if needed
        if (is_array($fields)) {
            $fieldSelector = ContactFieldSelector::make()->fields($fields);
        } else {
            $fieldSelector = $fields;
        }

        $response = $this->connector->send(
            new GetContact($contactId, $fieldSelector)
        );

        return $response->json();
    }

    /**
     * Create a new contact
     *
     * @param  array{
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     addresses?: array<int, array{line1?: string, line2?: string, locality?: string, region?: string, postal_code?: string, zip_code?: string, country_code?: string, field: string}>,
     *     company?: array{id?: int, company_name?: string},
     *     owner_id?: int,
     *     job_title?: string,
     *     ...
     * }  $data  Contact data
     * @return array{
     *     id: int,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string}>,
     *     owner_id?: int,
     *     date_created?: string,
     *     last_updated?: string,
     *     ...
     * }
     *
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
     * @param  int  $contactId  The contact ID to update
     * @param  array{
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     owner_id?: int,
     *     job_title?: string,
     *     ...
     * }  $data  Contact data to update
     * @return array{
     *     id: int,
     *     given_name?: string,
     *     family_name?: string,
     *     owner_id?: int,
     *     date_created?: string,
     *     last_updated?: string,
     *     ...
     * }
     *
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
     * @param  int  $contactId  The contact ID to delete
     *
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
     * @param  int  $contactId  The contact ID
     * @param  array|int  $tagIds  The tag IDs to apply
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function applyTags(int $contactId, array|int $tagIds): bool
    {
        $response = $this->connector->send(
            new ApplyTagToContact($contactId, $tagIds)
        );

        return $response->successful();
    }

    /**
     * Remove tags from contact
     *
     * Removes specific tags from a contact.
     *
     * @param  int  $contactId  The contact ID
     * @param  array|int  $tagIds  The tag IDs to remove
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeTags(int $contactId, array|int $tagIds): bool
    {
        $response = $this->connector->send(
            new RemoveTagsFromContact($contactId, $tagIds)
        );

        return $response->successful();
    }

    /**
     * Remove a tag from a contact
     *
     * @param  int  $contactId  The contact ID
     * @param  int  $tagId  The tag ID to remove
     *
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
     * @return array{
     *     custom_fields?: array<int, array{
     *         id: int,
     *         label: string,
     *         field_type: string,
     *         options?: array<int, array{id: int, label: string}>
     *     }>,
     *     optional_properties?: array<string>
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getModel(): array
    {
        return $this->connector->send(new GetContactModel)->json();
    }

    /**
     * Get contact tags
     *
     * Retrieves a list of tags applied to a contact.
     *
     * @param  int  $contactId  The contact ID
     * @param  int|null  $limit  Max number of results
     * @param  int|null  $offset  Starting offset
     * @return array{
     *     tags: array<int, array{
     *         id: int,
     *         name: string,
     *         description?: string,
     *         category?: array{id: int, name: string}
     *     }>,
     *     count: int
     * }
     *
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
     * @param  int  $contactId  The contact ID
     * @return array{
     *     emails?: array<int, array{
     *         id: int,
     *         subject?: string,
     *         sent_from_address?: string,
     *         sent_to_address?: string,
     *         sent_date?: string,
     *         ...
     *     }>
     * }
     *
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
     * Updates or creates a contact based on the duplicate_option field.
     *
     * @param  array{
     *     duplicate_option?: string,
     *     email?: string,
     *     given_name?: string,
     *     family_name?: string,
     *     ...
     * }  $data  Contact data
     * @return array{
     *     id: int,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string}>,
     *     ...
     * }
     *
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
     * @param  array{
     *     label: string,
     *     field_type: string,
     *     group_id?: int,
     *     options?: array<int, array{label: string}>
     * }  $data  Custom field data
     * @return array{
     *     id: int,
     *     label: string,
     *     field_type: string,
     *     options?: array<int, array{id: int, label: string}>
     * }
     *
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
     * @param  int  $contactId  The contact ID
     * @return array{
     *     credit_cards?: array<int, array{
     *         id: int,
     *         card_type?: string,
     *         last_four?: string,
     *         expiration_month?: string,
     *         expiration_year?: string,
     *         ...
     *     }>
     * }
     *
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
     * @param  int  $contactId  The contact ID
     * @param  array{
     *     card_number: string,
     *     card_type: string,
     *     expiration_month: string,
     *     expiration_year: string,
     *     cvv?: string
     * }  $data  Credit card data
     * @return array{
     *     id: int,
     *     card_type: string,
     *     last_four: string,
     *     expiration_month: string,
     *     expiration_year: string
     * }
     *
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
     * @param  int  $contactId  The contact ID
     * @param  array{
     *     subject: string,
     *     html_content?: string,
     *     plain_content?: string,
     *     sent_from_address: string,
     *     sent_to_address: string,
     *     ...
     * }  $data  Email data
     * @return array{
     *     id: int,
     *     subject: string,
     *     sent_from_address: string,
     *     sent_to_address: string,
     *     sent_date?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createEmail(int $contactId, array $data): array
    {
        return $this->connector->send(new CreateContactEmail($contactId, $data))->json();
    }

    /**
     * Add UTM to contact
     *
     * Adds UTM parameters to a contact for tracking.
     *
     * @param  int  $contactId  The contact ID
     * @param  array{
     *     utm_source?: string,
     *     utm_medium?: string,
     *     utm_campaign?: string,
     *     utm_term?: string,
     *     utm_content?: string
     * }  $data  UTM data
     * @return array{
     *     id: int,
     *     utm_source?: string,
     *     utm_medium?: string,
     *     utm_campaign?: string,
     *     utm_term?: string,
     *     utm_content?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function addUtm(int $contactId, array $data): array
    {
        return $this->connector->send(new AddUtmToContact($contactId, $data))->json();
    }
}
