<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\HasTimeout;

/**
 * Get Products (v2)
 *
 * Gets a single Product
 *
 * @see https://developer.keap.com/docs/restv2/#tag/Products/operation/getProductUsingGET
 */
class GetProduct extends Request
{
    use HasTimeout;

    protected int $requestTimeout = 60;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $productId
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/products/'.$this->productId;
    }
}
