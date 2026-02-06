<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Opportunities\ListOpportunityStages;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\OpportunityStageQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Opportunities Resource (v2)
 *
 * Provides methods for interacting with the Keap Opportunities API v2.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class OpportunitiesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List opportunity stages with sorting and pagination
     *
     * Returns a single page of results. Use newListStagesPaginator() to automatically
     * iterate through all pages.
     *
     * @param  OpportunityStageQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     stages: array<int, array{
     *         id?: string,
     *         name?: string,
     *         order?: int,
     *         probability?: int,
     *         target_number_days?: int,
     *         checklist_items?: array<int, array{id?: string, description?: string, order?: int, required?: bool}>,
     *         created_time?: string,
     *         updated_time?: string,
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listStages(?OpportunityStageQuery $query = null): array
    {
        $query = $query ?? OpportunityStageQuery::make();

        $response = $this->connector->send(new ListOpportunityStages($query));
        $data = $response->json();

        return [
            'stages' => $data['stages'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list opportunity stages endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  OpportunityStageQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListStagesPaginator(?OpportunityStageQuery $query = null): Paginator
    {
        $query = $query ?? OpportunityStageQuery::make();

        return new Paginator(
            fn (OpportunityStageQuery $q) => $this->listStages($q),
            $query,
            'stages'
        );
    }
}
