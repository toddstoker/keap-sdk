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
        protected readonly mixed $fileContents,
        protected readonly string $fileAssociation,
        protected readonly bool $isPublic,
        protected readonly ?int $contactId = null
    ) {}

    /**
     * @return array<int, MultipartValue>
     */
    protected function defaultBody(): array
    {
        $body = [
            new MultipartValue('file', $this->fileContents, $this->fileName),
            new MultipartValue('file_name', $this->fileName, null, ['Content-Type' => 'application/json']),
            new MultipartValue('file_association', '"'.$this->fileAssociation.'"', null, ['Content-Type' => 'application/json']), // API expects a JSON string
            new MultipartValue('is_public', $this->isPublic ? 'true' : 'false', null, ['Content-Type' => 'application/json']),
        ];

        if ($this->contactId !== null) {
            $body[] = new MultipartValue('contact_id', (string) $this->contactId);
        }

        return $body;
    }

    public function resolveEndpoint(): string
    {
        return '/v2/files';
    }
}
