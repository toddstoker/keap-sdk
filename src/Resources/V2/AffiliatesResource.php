<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Affiliates\ListAffiliates;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\AffiliateQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Affiliates Resource (v2)
 *
 * Provides methods for listing affiliates in Keap. Affiliates are contacts
 * who promote your products and earn commissions on referred sales.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class AffiliatesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List affiliates with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  AffiliateQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     affiliates: array<int, array{
     *         id: string,
     *         name: string,
     *         code: string,
     *         contact_id: string,
     *         status: string,
     *         date_created?: string,
     *         unique_site_id?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?AffiliateQuery $query = null): array
    {
        $query = $query ?? AffiliateQuery::make();

        $response = $this->connector->send(new ListAffiliates($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list affiliates endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  AffiliateQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?AffiliateQuery $query = null): Paginator
    {
        $query = $query ?? AffiliateQuery::make();

        return new Paginator(
            fn (AffiliateQuery $q) => $this->list($q),
            $query,
            'affiliates'
        );
    }
}
