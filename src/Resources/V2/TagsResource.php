<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Tags\ApplyTagToContacts;
use Toddstoker\KeapSdk\Requests\V2\Tags\CreateTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\CreateTagCategory;
use Toddstoker\KeapSdk\Requests\V2\Tags\DeleteTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\DeleteTagCategory;
use Toddstoker\KeapSdk\Requests\V2\Tags\GetTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\GetTagCategory;
use Toddstoker\KeapSdk\Requests\V2\Tags\ListCompaniesWithTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\ListContactsWithTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\ListTagCategories;
use Toddstoker\KeapSdk\Requests\V2\Tags\ListTags;
use Toddstoker\KeapSdk\Requests\V2\Tags\RemoveTagFromContacts;
use Toddstoker\KeapSdk\Requests\V2\Tags\UpdateTag;
use Toddstoker\KeapSdk\Requests\V2\Tags\UpdateTagCategory;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\TagQuery;

/**
 * Tags Resource (v2)
 *
 * Provides methods for managing tags, tag categories, and tag applications.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class TagsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List tags with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  TagQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     tags: array<int, array{
     *         id: int,
     *         name: string,
     *         description?: string,
     *         category?: array{id: int, name: string}
     *     }>,
     *     next_page_token: ?string
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
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  TagQuery|null  $query  Query builder with filters, sorting, and pagination options
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
     * Retrieves a single tag by ID.
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
     * Update a tag
     *
     * Updates an existing tag.
     *
     * @param  int  $tagId  The tag ID
     * @param  array{
     *     name?: string,
     *     description?: string,
     *     category?: array{id: int}
     * }  $data  Tag data to update
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
    public function update(int $tagId, array $data): array
    {
        $response = $this->connector->send(new UpdateTag($tagId, $data));

        return $response->json();
    }

    /**
     * Delete a tag
     *
     * Deletes a tag.
     *
     * @param  int  $tagId  The tag ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $tagId): bool
    {
        $response = $this->connector->send(new DeleteTag($tagId));

        return $response->successful();
    }

    /**
     * List tag categories
     *
     * Retrieves a list of tag categories.
     *
     * @return array{
     *     tag_categories: array<int, array{
     *         id: int,
     *         name: string,
     *         description?: string
     *     }>
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCategories(): array
    {
        $response = $this->connector->send(new ListTagCategories);

        return $response->json();
    }

    /**
     * Get a tag category
     *
     * Retrieves a single tag category by ID.
     *
     * @param  int  $tagCategoryId  The tag category ID
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getCategory(int $tagCategoryId): array
    {
        $response = $this->connector->send(new GetTagCategory($tagCategoryId));

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
     * Update a tag category
     *
     * Updates an existing tag category.
     *
     * @param  int  $tagCategoryId  The tag category ID
     * @param  array{
     *     name?: string,
     *     description?: string
     * }  $data  Tag category data to update
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function updateCategory(int $tagCategoryId, array $data): array
    {
        $response = $this->connector->send(new UpdateTagCategory($tagCategoryId, $data));

        return $response->json();
    }

    /**
     * Delete a tag category
     *
     * Deletes a tag category.
     *
     * @param  int  $tagCategoryId  The tag category ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function deleteCategory(int $tagCategoryId): bool
    {
        $response = $this->connector->send(new DeleteTagCategory($tagCategoryId));

        return $response->successful();
    }

    /**
     * Apply tag to contacts
     *
     * Applies a Tag to a list of Contacts.
     *
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  Array of contact IDs
     * @return array{
     *     success: bool,
     *     message?: string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function applyToContacts(int $tagId, array $contactIds): array
    {
        $response = $this->connector->send(
            new ApplyTagToContacts($tagId, $contactIds)
        );

        return $response->json();
    }

    /**
     * Remove tag from contacts
     *
     * Removes a Tag from a list of Contacts.
     *
     * @param  int  $tagId  The tag ID
     * @param  array<int>  $contactIds  Array of contact IDs
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function removeFromContacts(int $tagId, array $contactIds): bool
    {
        $response = $this->connector->send(
            new RemoveTagFromContacts($tagId, $contactIds)
        );

        return $response->successful();
    }

    /**
     * List contacts with tag
     *
     * Retrieves a list of contacts that have a specific tag.
     *
     * @param  int  $tagId  The tag ID
     * @param  int|null  $pageSize  Total number of items to return per page
     * @param  string|null  $pageToken  Page token for pagination
     * @return array{
     *     contacts: array<int, array{
     *         id: int,
     *         given_name?: string,
     *         family_name?: string,
     *         email_addresses?: array<int, array{email: string, field: string}>
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listContacts(
        int $tagId,
        ?int $pageSize = null,
        ?string $pageToken = null
    ): array {
        $response = $this->connector->send(
            new ListContactsWithTag($tagId, $pageSize, $pageToken)
        );

        return $response->json();
    }

    /**
     * List companies with tag
     *
     * Retrieves a list of companies that have a specific tag.
     *
     * @param  int  $tagId  The tag ID
     * @param  int|null  $pageSize  Total number of items to return per page
     * @param  string|null  $pageToken  Page token for pagination
     * @return array{
     *     companies: array<int, array{
     *         id: int,
     *         company_name?: string,
     *         email_addresses?: array<int, array{email: string, field: string}>
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCompanies(
        int $tagId,
        ?int $pageSize = null,
        ?string $pageToken = null
    ): array {
        $response = $this->connector->send(
            new ListCompaniesWithTag($tagId, $pageSize, $pageToken)
        );

        return $response->json();
    }
}
