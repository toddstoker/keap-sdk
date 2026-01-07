<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Files;

use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;
use Saloon\Traits\Plugins\HasTimeout;

class UploadFile extends Request implements HasBody
{
    use HasMultipartBody, HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::POST;

    /**
     * @param  string  $fileName  File name
     * @param  string  $fileContents  File contents (binary data)
     * @param  string  $fileAssociation  File association (CONTACT, USER, or COMPANY)
     * @param  bool  $isPublic  Whether the file is public
     * @param  int|null  $contactId  Contact ID (required if file_association is CONTACT)
     */
    public function __construct(
        protected readonly string $fileName,
        protected readonly string $fileContents,
        protected readonly string $fileAssociation,
        protected readonly bool $isPublic,
        protected readonly ?int $contactId = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/files';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'file' => new MultipartValue($this->fileContents, $this->fileName),
            'file_name' => $this->fileName,
            'file_association' => $this->fileAssociation,
            'is_public' => $this->isPublic ? 'true' : 'false',
        ];

        if ($this->contactId !== null) {
            $body['contact_id'] = (string) $this->contactId;
        }

        return $body;
    }
}
