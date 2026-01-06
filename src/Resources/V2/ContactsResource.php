<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Contacts\CreateContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\DeleteContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\GetContact;
use Toddstoker\KeapSdk\Requests\V2\Contacts\GetContactModel;
use Toddstoker\KeapSdk\Requests\V2\Contacts\GetLeadScore;
use Toddstoker\KeapSdk\Requests\V2\Contacts\LinkContacts;
use Toddstoker\KeapSdk\Requests\V2\Contacts\ListContactLinks;
use Toddstoker\KeapSdk\Requests\V2\Contacts\ListContactLinkTypes;
use Toddstoker\KeapSdk\Requests\V2\Contacts\ListContacts;
use Toddstoker\KeapSdk\Requests\V2\Contacts\UnlinkContacts;
use Toddstoker\KeapSdk\Requests\V2\Contacts\UpdateContact;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\ContactQuery;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\ContactFieldSelector;
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
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email_addresses?: array<int, array{
     *             email: string,
     *             field: string,
     *             email_opt_status?: string,
     *             is_opt_in?: bool,
     *             opt_in_reason?: string
     *         }>,
     *         phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *         company?: array{id: string, company_name?: string},
     *         owner_id?: string,
     *         create_time?: string,
     *         update_time?: string,
     *         ...
     *     }>,
     *     next_page_token: ?string
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
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list contacts endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
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
     * a ContactFieldSelector instance, or null to get all available fields.
     *
     * @param  int  $contactId  The contact ID
     * @param  ContactFieldSelector|array<string>|null  $fields  Fields to include in response
     * @return array{
     *     id: string,
     *     given_name?: string,
     *     family_name?: string,
     *     middle_name?: string,
     *     preferred_name?: string,
     *     prefix?: string,
     *     suffix?: string,
     *     email_addresses?: array<int, array{
     *         email: string,
     *         field: string,
     *         email_opt_status?: string,
     *         is_opt_in?: bool,
     *         opt_in_reason?: string
     *     }>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string, extension?: string}>,
     *     fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     addresses?: array<int, array{
     *         country?: string,
     *         country_code?: string,
     *         line1?: string,
     *         line2?: string,
     *         locality?: string,
     *         postal_code?: string,
     *         region?: string,
     *         region_code?: string,
     *         zip_code?: string,
     *         zip_four?: string,
     *         field: string
     *     }>,
     *     company?: array{id: string, company_name?: string},
     *     job_title?: string,
     *     website?: string,
     *     birth_date?: string,
     *     anniversary_date?: string,
     *     spouse_name?: string,
     *     time_zone?: string,
     *     preferred_locale?: string,
     *     tag_ids?: array<string>,
     *     create_time?: string,
     *     update_time?: string,
     *     owner_id?: string,
     *     leadsource_id?: string,
     *     source_type?: string,
     *     contact_type?: string,
     *     score_value?: string,
     *     referral_code?: string,
     *     custom_fields?: array<int, array{id: string, content: mixed}>,
     *     origin?: array{date: string, ip_address: string},
     *     social_accounts?: array<int, array{name: string, type: string}>,
     *     links?: array<int, array{id: string, link_type_id: string, link_type_name: string, linked_contact_id: string}>,
     *     utm_parameters?: array<int, array{dateCreated: string, firstTouch: bool, id: int, keapSourceId: string, lastTouch: bool, utmCampaign: string, utmContent: string, utmMedium: string, utmSource: string, utmTerm: string}>,
     *     ...
     * }
     * @phpstan-return array<string, mixed>
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

        $response = $this->connector->send(new GetContact($contactId, $fieldSelector));

        return $response->json();
    }

    /**
     * Create a new contact
     *
     * @param  array{
     *     given_name?: string,
     *     family_name?: string,
     *     middle_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string, is_opt_in?: bool, opt_in_reason?: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     company?: array{id?: string, company_name?: string},
     *     owner_id?: string,
     *     job_title?: string,
     *     ...
     * }  $data  Contact data
     * @return array{
     *     id: string,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string, email_opt_status?: string, is_opt_in?: bool}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     owner_id?: string,
     *     create_time?: string,
     *     update_time?: string,
     *     ...
     * }
     * @phpstan-return array<string, mixed>
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
     *     middle_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string, is_opt_in?: bool, opt_in_reason?: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     company?: array{id?: string, company_name?: string},
     *     owner_id?: string,
     *     job_title?: string,
     *     ...
     * }  $data  Contact data to update
     * @return array{
     *     id: string,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string, email_opt_status?: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     owner_id?: string,
     *     create_time?: string,
     *     update_time?: string,
     *     ...
     * }
     * @phpstan-return array<string, mixed>
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
     * Get lead score for a contact
     *
     * Retrieves information about the Lead Score of a Contact.
     *
     * @param  int  $contactId  The contact ID
     * @return array{
     *     last_updated?: string,
     *     score?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getLeadScore(int $contactId): array
    {
        $response = $this->connector->send(new GetLeadScore($contactId));

        return $response->json();
    }

    /**
     * List linked contacts for a given contact
     *
     * Retrieves a list of Linked Contacts for a given Contact.
     *
     * @param  int  $contactId  The contact ID
     * @return array{
     *     links: array<int, array{
     *         contact1_id: string,
     *         contact2_id: string,
     *         link_type_id: string,
     *         id: string
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listLinks(int $contactId): array
    {
        $response = $this->connector->send(new ListContactLinks($contactId));

        return $response->json();
    }

    /**
     * Link two contacts together
     *
     * Links two Contacts together using the provided Link type.
     *
     * @param  int  $contact1Id  The first contact ID
     * @param  int  $contact2Id  The second contact ID
     * @param  int  $linkTypeId  The link type ID
     * @return array{
     *     id: string,
     *     contact1_id: string,
     *     contact2_id: string,
     *     link_type_id: string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function linkContacts(int $contact1Id, int $contact2Id, int $linkTypeId): array
    {
        $response = $this->connector->send(
            new LinkContacts($contact1Id, $contact2Id, $linkTypeId)
        );

        return $response->json();
    }

    /**
     * Unlink two contacts
     *
     * Deletes Link between two Contacts.
     *
     * @param  int  $contact1Id  The first contact ID
     * @param  int  $contact2Id  The second contact ID
     * @param  int  $linkTypeId  The link type ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function unlinkContacts(int $contact1Id, int $contact2Id, int $linkTypeId): bool
    {
        $response = $this->connector->send(
            new UnlinkContacts($contact1Id, $contact2Id, $linkTypeId)
        );

        return $response->successful();
    }

    /**
     * List contact link types
     *
     * Retrieves a list of Contact Link types.
     *
     * @param  string|null  $filter  Filter to apply (e.g., "name==expectedValue")
     * @param  string|null  $orderBy  Field and direction to order by (e.g., "name asc")
     * @param  int|null  $pageSize  Total number of items to return per page
     * @param  string|null  $pageToken  Page token for pagination
     * @return array{
     *     contact_link_types: array<int, array{
     *         id: string,
     *         name: string
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listLinkTypes(
        ?string $filter = null,
        ?string $orderBy = null,
        ?int $pageSize = null,
        ?string $pageToken = null
    ): array {
        $response = $this->connector->send(
            new ListContactLinkTypes($filter, $orderBy, $pageSize, $pageToken)
        );

        return $response->json();
    }

    /**
     * Get contact model
     *
     * Get the custom fields and optional properties for the Contact object.
     *
     * @return array{custom_fields: array<array<string, mixed>>, optional_properties: array<string>}
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getModel(): array
    {
        $response = $this->connector->send(new GetContactModel);

        return $response->json();
    }
}
