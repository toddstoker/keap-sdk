<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Notes\CreateNote;
use Toddstoker\KeapSdk\Requests\V2\Notes\DeleteNote;
use Toddstoker\KeapSdk\Requests\V2\Notes\GetNote;
use Toddstoker\KeapSdk\Requests\V2\Notes\ListNotes;
use Toddstoker\KeapSdk\Requests\V2\Notes\UpdateNote;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\NoteQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Notes Resource (v2)
 *
 * Provides methods for interacting with the Keap Notes API v2.
 * Notes are associated with specific contacts.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class NotesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List notes for a contact with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  int  $contactId  The contact ID
     * @param  NoteQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     notes: array<int, array{
     *         id: string,
     *         contact_id: string,
     *         title?: string,
     *         type?: string,
     *         text?: string,
     *         assigned_to_user?: array{
     *             id: string,
     *             given_name?: string,
     *             family_name?: string,
     *             email_address?: string
     *         },
     *         created_by_user_id?: string,
     *         last_updated_by_user_id?: string,
     *         pinned_at?: string,
     *         create_time?: string,
     *         update_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function list(int $contactId, ?NoteQuery $query = null): array
    {
        $query = $query ?? NoteQuery::make();

        $response = $this->connector->send(new ListNotes($contactId, $query));
        $data = $response->json();

        return [
            'notes' => $data['notes'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through notes for a contact
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  int  $contactId  The contact ID
     * @param  NoteQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(int $contactId, ?NoteQuery $query = null): Paginator
    {
        $query = $query ?? NoteQuery::make();

        return new Paginator(
            fn (NoteQuery $q) => $this->list($contactId, $q),
            $query,
            'notes'
        );
    }

    /**
     * Get a specific note by ID
     *
     * @param  int  $contactId  The contact ID
     * @param  int  $noteId  The note ID
     * @return array{
     *     id: string,
     *     contact_id: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     assigned_to_user?: array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email_address?: string
     *     },
     *     created_by_user_id?: string,
     *     last_updated_by_user_id?: string,
     *     pinned_at?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function get(int $contactId, int $noteId): array
    {
        $response = $this->connector->send(new GetNote($contactId, $noteId));

        return $response->json();
    }

    /**
     * Create a new note for a contact
     *
     * Either title or type is required.
     *
     * @param  int  $contactId  The contact ID
     * @param  array{
     *     user_id: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     is_pinned?: bool
     * }  $data  Note data (user_id required, title or type required)
     * @return array{
     *     id: string,
     *     contact_id: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     assigned_to_user?: array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email_address?: string
     *     },
     *     created_by_user_id?: string,
     *     last_updated_by_user_id?: string,
     *     pinned_at?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function create(int $contactId, array $data): array
    {
        $response = $this->connector->send(new CreateNote($contactId, $data));

        return $response->json();
    }

    /**
     * Update an existing note
     *
     * @param  int  $contactId  The contact ID
     * @param  int  $noteId  The note ID to update
     * @param  array{
     *     user_id: string,
     *     contact_id?: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     is_pinned?: bool
     * }  $data  Note data to update (user_id required)
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     * @return array{
     *     id: string,
     *     contact_id: string,
     *     title?: string,
     *     type?: string,
     *     text?: string,
     *     assigned_to_user?: array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email_address?: string
     *     },
     *     created_by_user_id?: string,
     *     last_updated_by_user_id?: string,
     *     pinned_at?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function update(int $contactId, int $noteId, array $data, ?array $updateMask = null): array
    {
        $response = $this->connector->send(
            new UpdateNote($contactId, $noteId, $data, $updateMask)
        );

        return $response->json();
    }

    /**
     * Delete a note
     *
     * @param  int  $contactId  The contact ID
     * @param  int  $noteId  The note ID to delete
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $contactId, int $noteId): bool
    {
        $response = $this->connector->send(new DeleteNote($contactId, $noteId));

        return $response->successful();
    }
}
