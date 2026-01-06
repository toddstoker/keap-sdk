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
use Toddstoker\KeapSdk\Support\V2\TagCategoryQuery;
use Toddstoker\KeapSdk\Support\V2\TagCompanyQuery;
use Toddstoker\KeapSdk\Support\V2\TagContactQuery;
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
     *         id: string,
     *         name: string,
     *         description?: string,
     *         category?: array{id: string, name: string}
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
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
            $query,
            'tags'
        );
    }

    /**
     * Get a tag
     *
     * Retrieves a single tag by ID.
     *
     * @param  int  $tagId  The tag ID
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     category?: array{id: string, name: string}
     * }
     * @phpstan-return array<string, mixed>
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
     *     category?: array{id: string}
     * }  $data  Tag data
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     category?: array{id: string, name: string}
     * }
     * @phpstan-return array<string, mixed>
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
     *     category?: array{id: string}
     * }  $data  Tag data to update
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     category?: array{id: string, name: string}
     * }
     * @phpstan-return array<string, mixed>
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
     * List tag categories with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListCategoriesPaginator() to automatically
     * iterate through all pages.
     *
     * @param  TagCategoryQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     tag_categories: array<int, array{
     *         id: string,
     *         name: string,
     *         description?: string,
     *         create_time?: string,
     *         update_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCategories(?TagCategoryQuery $query = null): array
    {
        $query = $query ?? TagCategoryQuery::make();

        $response = $this->connector->send(new ListTagCategories($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list tag categories endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  TagCategoryQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListCategoriesPaginator(?TagCategoryQuery $query = null): Paginator
    {
        $query = $query ?? TagCategoryQuery::make();

        return new Paginator(
            fn (TagCategoryQuery $q) => $this->listCategories($q),
            $query,
            'tag_categories'
        );
    }

    /**
     * Get a tag category
     *
     * Retrieves a single tag category by ID.
     *
     * @param  int  $tagCategoryId  The tag category ID
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string
     * }
     * @phpstan-return array<string, mixed>
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
     *     id: string,
     *     name: string,
     *     description?: string
     * }
     * @phpstan-return array<string, mixed>
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
     *     id: string,
     *     name: string,
     *     description?: string
     * }
     * @phpstan-return array<string, mixed>
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
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function applyToContacts(int $tagId, int|array $contactIds): array
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
    public function removeFromContacts(int $tagId, int|array $contactIds): bool
    {
        $response = $this->connector->send(
            new RemoveTagFromContacts($tagId, $contactIds)
        );

        return $response->successful();
    }

    /**
     * List contacts with tag
     *
     * Retrieves a list of contacts that have a specific tag with filtering,
     * sorting, and pagination.
     *
     * Returns a single page of results. Use newListContactsPaginator() to automatically
     * iterate through all pages.
     *
     * @param  int  $tagId  The tag ID
     * @param  TagContactQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     contacts: array<int, array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email?: string,
     *         applied_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listContacts(
        int $tagId,
        ?TagContactQuery $query = null
    ): array {
        $query = $query ?? TagContactQuery::make();

        $response = $this->connector->send(
            new ListContactsWithTag($tagId, $query)
        );

        return $response->json();
    }

    /**
     * Create a paginator for iterating through contacts with a specific tag.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  int  $tagId  The tag ID
     * @param  TagContactQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListContactsPaginator(int $tagId, ?TagContactQuery $query = null): Paginator
    {
        $query = $query ?? TagContactQuery::make();

        return new Paginator(
            fn (TagContactQuery $q) => $this->listContacts($tagId, $q),
            $query,
            'contacts'
        );
    }

    /**
     * List companies with tag
     *
     * Retrieves a list of companies that have a specific tag with filtering,
     * sorting, and pagination.
     *
     * Returns a single page of results. Use newListCompaniesPaginator() to automatically
     * iterate through all pages.
     *
     * @param  int  $tagId  The tag ID
     * @param  TagCompanyQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     tagged_companies: array<int, array{
     *         applied_time?: string,
     *         company: array{
     *             id: string,
     *             company_name?: string,
     *             email_address?: array{
     *                 email: string,
     *                 field: string,
     *                 email_opt_status?: string,
     *                 is_opt_in?: bool,
     *                 opt_in_reason?: string
     *             }
     *         }
     *     }>,
     *     next_page_token: ?string
     * }
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCompanies(
        int $tagId,
        ?TagCompanyQuery $query = null
    ): array {
        $query = $query ?? TagCompanyQuery::make();

        $response = $this->connector->send(
            new ListCompaniesWithTag($tagId, $query)
        );

        return $response->json();
    }

    /**
     * Create a paginator for iterating through companies with a specific tag.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  int  $tagId  The tag ID
     * @param  TagCompanyQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListCompaniesPaginator(int $tagId, ?TagCompanyQuery $query = null): Paginator
    {
        $query = $query ?? TagCompanyQuery::make();

        return new Paginator(
            fn (TagCompanyQuery $q) => $this->listCompanies($tagId, $q),
            $query,
            'tagged_companies'
        );
    }
}
