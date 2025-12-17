<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Tags\ApplyTagToContacts;
use Toddstoker\KeapSdk\Requests\V1\Tags\CreateTag;
use Toddstoker\KeapSdk\Requests\V1\Tags\CreateTagCategory;
use Toddstoker\KeapSdk\Requests\V1\Tags\GetTag;
use Toddstoker\KeapSdk\Requests\V1\Tags\ListCompaniesWithTag;
use Toddstoker\KeapSdk\Requests\V1\Tags\ListContactsWithTag;
use Toddstoker\KeapSdk\Requests\V1\Tags\ListTags;
use Toddstoker\KeapSdk\Requests\V1\Tags\RemoveTagFromContact;
use Toddstoker\KeapSdk\Requests\V1\Tags\RemoveTagFromContacts;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V1\Paginator;
use Toddstoker\KeapSdk\Support\V1\TagQuery;

/**
 * Tags Resource (v1)
 *
 * Provides methods for managing tags and tag applications.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class TagsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List tags with filtering and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  TagQuery|null  $query  Query builder with filters and pagination options
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
    public function list(?TagQuery $query = null): array
    {
        $query = $query ?? TagQuery::make();

        $response = $this->connector->send(new ListTags($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list tags endpoint.
     *
     * Automatically fetches subsequent pages using offset-based pagination.
     *
     * @param  TagQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(?TagQuery $query = null): Paginator
    {
        $query = $query ?? TagQuery::make();

        return new Paginator(
            fn (TagQuery $q) => $this->list($q),
            $query
        );
    }

    /**
     * Get a tag
     *
     * Retrieves a single tag.
     *
     * @param  int  $tagId  The tag ID
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: string,
     *     category?: array{id: int, name: string}
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $tagId): array
    {
        $response = $this->connector->send(new GetTag($tagId));

        return $response->json();
    }

    /**
     * Create a tag
     *
     * Creates a new tag.
     *
     * @param  array{
     *     name: string,
     *     description?: string,
     *     category?: array{id: int}
     * }  $data  Tag data
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: string,
     *     category?: array{id: int, name: string}
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        $response = $this->connector->send(new CreateTag($data));

        return $response->json();
    }

    /**
     * Create a tag category
     *
     * Creates a new tag category.
     *
     * @param  array{
     *     name: string,
     *     description?: string
     * }  $data  Tag category data
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function createCategory(array $data): array
    {
        $response = $this->connector->send(new CreateTagCategory($data));

        return $response->json();
    }

    /**
     * List companies with tag
     *
     * Retrieves a list of companies that have the given tag applied.
     *
     * @param  int  $tagId  The tag ID
     * @param  int|null  $limit  Max number of results (default 1000)
     * @param  int|null  $offset  Starting offset
     * @return array{
     *     companies: array<int, array{
     *         id: int,
     *         company_name?: string,
     *         email_addresses?: array<int, array{email: string, field: string}>
     *     }>,
     *     count: int
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCompanies(int $tagId, ?int $limit = null, ?int $offset = null): array
    {
        $response = $this->connector->send(new ListCompaniesWithTag($tagId, $limit, $offset));

        return $response->json();
    }

    /**
     * List contacts with tag
     *
     * Retrieves a list of contacts that have the given tag applied.
     *
     * @param  int  $tagId  The tag ID
     * @param  int|null  $limit  Max number of results (default 1000)
     * @param  int|null  $offset  Starting offset
     * @return array{contacts: array<array<string, mixed>>, count: int}
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listContacts(int $tagId, ?int $limit = null, ?int $offset = null): array
    {
        $response = $this->connector->send(new ListContactsWithTag($tagId, $limit, $offset));

        return $response->json();
    }

    /**
     * Apply tag to contacts
     *
     * Applies a tag to a list of contacts.
     *
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  Array of contact IDs
     * @return array<array<string, mixed>>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function applyToContacts(int $tagId, array $contactIds): array
    {
        $response = $this->connector->send(new ApplyTagToContacts($tagId, $contactIds));

        return $response->json();
    }

    /**
     * Remove tag from contacts
     *
     * Removes a tag from a list of contacts.
     *
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  Array of contact IDs
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeFromContacts(int $tagId, array $contactIds): bool
    {
        $response = $this->connector->send(new RemoveTagFromContacts($tagId, $contactIds));

        return $response->successful();
    }

    /**
     * Remove tag from contact
     *
     * Removes a tag from a single contact.
     *
     * @param  int  $tagId  The tag ID
     * @param  int  $contactId  The contact ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeFromContact(int $tagId, int $contactId): bool
    {
        $response = $this->connector->send(new RemoveTagFromContact($tagId, $contactId));

        return $response->successful();
    }
}
