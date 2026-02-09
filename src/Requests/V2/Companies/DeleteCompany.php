<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Company (v2)
 *
 * Deletes a company permanently.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class DeleteCompany extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $companyId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/companies/{$this->companyId}";
    }
}
