<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\CompanyFieldSelector;

/**
 * Get Company (v2)
 *
 * Retrieves a single company by ID.
 *
 * Supports optional field selection to specify which company properties
 * to include in the response.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetCompany extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $companyId,
        protected readonly ?CompanyFieldSelector $fieldSelector = null
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
        return $this->fieldSelector?->toArray() ?? [];
    }
}
