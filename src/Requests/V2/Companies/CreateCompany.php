<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Companies;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Company (v2)
 *
 * Creates a new company.
 *
 * Required field: company_name
 * Note: country_code is required if region is specified.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class CreateCompany extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data  Company data (company_name required)
     */
    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/companies';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
