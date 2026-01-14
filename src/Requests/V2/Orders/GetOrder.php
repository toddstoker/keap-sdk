<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\OrderFieldSelector;

/**
 * Get Order (v2)
 *
 * Retrieves a single order by ID.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetOrder extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string|int $orderId,
        protected readonly ?OrderFieldSelector $fieldSelector = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/orders/{$this->orderId}";
    }

    protected function defaultQuery(): array
    {
        return $this->fieldSelector?->toArray() ?? [];
    }
}
