<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Products\ListProducts;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\ProductQuery;

/**
 * Products Resource (v2)
 *
 * Provides methods for interacting with the Keap Products API v2.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class ProductsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List products with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  ProductQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     products: array<int, array{
     *         id: string,
     *         name?: string,
     *         short_description?: string,
     *         description?: string,
     *         sku?: string,
     *         price?: array{amount: int, currency_code: string, formatted_amount: string},
     *         active?: bool,
     *         shippable?: bool,
     *         taxable?: bool,
     *         state_taxable?: bool,
     *         city_taxable?: bool,
     *         country_taxable?: bool,
     *         weight?: float,
     *         subscription_only?: bool,
     *         storefront_hidden?: bool,
     *         categories?: array<string>,
     *         inventory?: array{
     *             email_for_inventory_notifications?: string,
     *             inventory_count?: int,
     *             inventory_limit?: int,
     *             out_of_stock_enabled?: bool
     *         },
     *         options?: array<int, array<string, mixed>>,
     *         subscription_plans?: array<int, array<string, mixed>>,
     *         ...
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?ProductQuery $query = null): array
    {
        $query = $query ?? ProductQuery::make();

        $response = $this->connector->send(new ListProducts($query));
        $data = $response->json();

        return [
            'products' => $data['products'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list products endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  ProductQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?ProductQuery $query = null): Paginator
    {
        $query = $query ?? ProductQuery::make();

        return new Paginator(
            fn (ProductQuery $q) => $this->list($q),
            $query,
            'products'
        );
    }
}
