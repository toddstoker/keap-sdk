<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Goals;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Achieve an Automation Goal (v2)
 *
 * Triggers the achievement of a specified automation goal for a contact.
 */
class Achieve extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $contactId,
        protected readonly string $integration,
        protected readonly string $callName,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/automations/goals/achieve';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'contact_id' => $this->contactId,
            'integration' => $this->integration,
            'call_name' => $this->callName,
        ];
    }
}
