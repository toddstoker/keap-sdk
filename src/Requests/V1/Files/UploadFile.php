<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Files;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UploadFile extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $fileName  File name
     * @param  string  $fileData  Base64-encoded file contents
     * @param  string  $fileAssociation  File association (CONTACT, USER, or COMPANY)
     * @param  bool  $isPublic  Whether the file is public
     * @param  int|null  $contactId  Contact ID (required if file_association is CONTACT)
     */
    public function __construct(
        protected readonly string $fileName,
        protected readonly string $fileData,
        protected readonly string $fileAssociation,
        protected readonly bool $isPublic,
        protected readonly ?int $contactId = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/files';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'file_name' => $this->fileName,
            'file_data' => $this->fileData,
            'file_association' => $this->fileAssociation,
            'is_public' => $this->isPublic,
        ];

        if ($this->contactId !== null) {
            $body['contact_id'] = $this->contactId;
        }

        return $body;
    }
}
