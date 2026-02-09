<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Companies;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Company (v2)
 *
 * Updates an existing company.
 *
 * Supports partial updates via the update_mask parameter to specify
 * which fields to update.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class UpdateCompany extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  int  $companyId  The company ID to update
     * @param  array<string, mixed>  $data  Company data to update
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     */
    public function __construct(
        protected readonly int $companyId,
        protected readonly array $data,
        protected readonly ?array $updateMask = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/companies/{$this->companyId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        if ($this->updateMask === null) {
            return [];
        }

        return [
            'update_mask' => implode(',', $this->updateMask),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
