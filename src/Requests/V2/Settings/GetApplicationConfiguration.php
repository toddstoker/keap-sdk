<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Settings;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetApplicationConfiguration extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string>|null  $fields  Optional fields to include in response (AFFILIATE, APPOINTMENT, CONTACT, ECOMMERCE, EMAIL, FORMS, FULFILLMENT, INVOICE, NOTE, OPPORTUNITY, TASK, TEMPLATE)
     */
    public function __construct(
        protected readonly ?array $fields = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/settings/applications:getConfiguration';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        if ($this->fields === null || empty($this->fields)) {
            return [];
        }

        return [
            'fields' => implode(',', $this->fields),
        ];
    }
}
