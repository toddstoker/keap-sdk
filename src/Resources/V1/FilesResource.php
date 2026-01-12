<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Files\UploadFile;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Files Resource (v1)
 *
 * Provides methods for uploading and managing files.
 * The v1 API uses JSON with base64-encoded file data.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class FilesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * Upload a new file
     *
     * The v1 API requires files to be base64-encoded and sent as JSON.
     * This is simpler and more reliable than the v2 multipart approach.
     *
     * @param  string  $fileName  File name
     * @param  string  $fileContents  Raw file contents (will be base64-encoded automatically)
     * @param  string  $fileAssociation  File association (CONTACT, USER, or COMPANY)
     * @param  bool  $isPublic  Whether the file is public
     * @param  int|null  $contactId  Contact ID (required if file_association is CONTACT)
     * @return array{
     *     file_descriptor: array{
     *         id: int,
     *         file_name?: string,
     *         file_size?: int,
     *         category?: string,
     *         file_box_type?: string,
     *         contact_id?: int,
     *         created_by?: int,
     *         date_created?: string,
     *         last_updated?: string,
     *         public?: bool,
     *         remote_file_key?: string,
     *         download_url?: string
     *     },
     *     file_data?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function upload(
        string $fileName,
        string $fileContents,
        string $fileAssociation,
        bool $isPublic,
        ?int $contactId = null
    ): array {
        // Base64 encode the file contents
        $fileData = base64_encode($fileContents);

        $response = $this->connector->send(
            new UploadFile($fileName, $fileData, $fileAssociation, $isPublic, $contactId)
        );

        return $response->json();
    }
}
