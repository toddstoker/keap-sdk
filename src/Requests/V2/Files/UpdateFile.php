<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Files;

use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

class UpdateFile extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $fileId  File ID
     * @param  string|null  $fileName  New file name
     * @param  string|null  $fileContents  New file contents (binary data)
     * @param  bool|null  $isPublic  Whether the file is public
     * @param  array<string>|null  $updateMask  Fields to update (file, file_name, is_public)
     */
    public function __construct(
        protected readonly int $fileId,
        protected readonly ?string $fileName = null,
        protected readonly ?string $fileContents = null,
        protected readonly ?bool $isPublic = null,
        protected readonly ?array $updateMask = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/files/{$this->fileId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->fileContents !== null && $this->fileName !== null) {
            $body['file'] = new MultipartValue($this->fileContents, $this->fileName);
        }

        if ($this->fileName !== null) {
            $body['file_name'] = $this->fileName;
        }

        if ($this->isPublic !== null) {
            $body['is_public'] = $this->isPublic ? 'true' : 'false';
        }

        if ($this->updateMask !== null) {
            $body['update_mask'] = implode(',', $this->updateMask);
        }

        return $body;
    }
}
