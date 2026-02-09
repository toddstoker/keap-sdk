<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\LeadSources;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateLeadSource extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

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
        protected readonly int $leadSourceId,
        protected readonly array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/leadSources/{$this->leadSourceId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
