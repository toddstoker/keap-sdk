<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateLeadSource extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array{
     *     name?: string,
     *     description?: string,
     *     lead_source_category_id?: string,
     *     vendor?: string,
     *     medium?: string,
     *     message?: string,
     *     start_time?: string,
     *     end_time?: string,
     *     status?: string
     * }  $data
     */
    public function __construct(
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return '/leadSources';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
