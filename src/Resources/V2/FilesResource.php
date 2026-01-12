<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Files\DeleteFile;
use Toddstoker\KeapSdk\Requests\V2\Files\GetFile;
use Toddstoker\KeapSdk\Requests\V2\Files\GetFileData;
use Toddstoker\KeapSdk\Requests\V2\Files\ListFiles;
use Toddstoker\KeapSdk\Requests\V2\Files\UpdateFile;
use Toddstoker\KeapSdk\Requests\V2\Files\UploadFile;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\FileQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Files Resource (v2)
 *
 * Provides methods for interacting with the Keap Files API v2.
 * Supports uploading, downloading, listing, updating, and deleting files.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class FilesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List files with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  FileQuery|null  $queryBuilder  Query builder with filters and pagination options
     * @return array{
     *     files: array<int, array{
     *         id: string,
     *         file_name?: string,
     *         file_size?: int,
     *         category?: string,
     *         file_box_type?: string,
     *         contact_id?: string,
     *         created_by_id?: string,
     *         is_public?: bool,
     *         remote_file_key?: string,
     *         created_time?: string,
     *         updated_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function list(?FileQuery $queryBuilder = null): array
    {
        $queryBuilder = $queryBuilder ?? FileQuery::make();

        $response = $this->connector->send(new ListFiles($queryBuilder));
        $data = $response->json();

        return [
            'files' => $data['files'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through files
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  FileQuery|null  $queryBuilder  Query builder with filters and pagination options
     */
    public function newListPaginator(?FileQuery $queryBuilder = null): Paginator
    {
        $queryBuilder = $queryBuilder ?? FileQuery::make();

        return new Paginator(
            fn (FileQuery $q) => $this->list($q),
            $queryBuilder,
            'files'
        );
    }

    /**
     * Get file metadata by ID
     *
     * @param  int  $fileId  The file ID
     * @return array{
     *     id: string,
     *     file_name?: string,
     *     file_size?: int,
     *     category?: string,
     *     file_box_type?: string,
     *     contact_id?: string,
     *     created_by_id?: string,
     *     is_public?: bool,
     *     remote_file_key?: string,
     *     created_time?: string,
     *     updated_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function get(int $fileId): array
    {
        $response = $this->connector->send(new GetFile($fileId));

        return $response->json();
    }

    /**
     * Download file data (binary content)
     *
     * @param  int  $fileId  The file ID
     * @return string Binary file contents
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function download(int $fileId): string
    {
        $response = $this->connector->send(new GetFileData($fileId));

        return $response->body();
    }

    /**
     * Upload a new file
     *
     * @param  string  $fileName  File name
     * @param  mixed  $fileContents  File contents (binary data or resource)
     * @param  string  $fileAssociation  File association (CONTACT, USER, or COMPANY)
     * @param  bool  $isPublic  Whether the file is public
     * @param  int|null  $contactId  Contact ID (required if file_association is CONTACT)
     * @return array{
     *     id: string,
     *     file_name?: string,
     *     file_size?: int,
     *     category?: string,
     *     file_box_type?: string,
     *     contact_id?: string,
     *     created_by_id?: string,
     *     is_public?: bool,
     *     remote_file_key?: string,
     *     created_time?: string,
     *     updated_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function upload(
        string $fileName,
        mixed $fileContents,
        string $fileAssociation,
        bool $isPublic,
        ?int $contactId = null
    ): array {
        $response = $this->connector->send(
            new UploadFile($fileName, $fileContents, $fileAssociation, $isPublic, $contactId)
        );

        return $response->json();
    }

    /**
     * Delete a file
     *
     * @param  int  $fileId  The file ID to delete
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $fileId): bool
    {
        $response = $this->connector->send(new DeleteFile($fileId));

        return $response->successful();
    }
}
