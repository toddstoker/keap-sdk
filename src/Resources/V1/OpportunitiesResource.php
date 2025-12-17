<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Opportunities\CreateOpportunity;
use Toddstoker\KeapSdk\Requests\V1\Opportunities\GetOpportunity;
use Toddstoker\KeapSdk\Requests\V1\Opportunities\GetOpportunityModel;
use Toddstoker\KeapSdk\Requests\V1\Opportunities\ListOpportunities;
use Toddstoker\KeapSdk\Requests\V1\Opportunities\UpdateOpportunity;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V1\OpportunityQuery;
use Toddstoker\KeapSdk\Support\V1\Paginator;

/**
 * Opportunities Resource (v1)
 *
 * Provides methods for interacting with the Keap Opportunities API v1.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class OpportunitiesResource implements Resource
{
    public function __construct(protected Keap $connector) {}

    /**
     * List opportunities with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  OpportunityQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?OpportunityQuery $query = null): array
    {
        $query = $query ?? OpportunityQuery::make();

        return $this->connector->send(new ListOpportunities($query))->json();
    }

    /**
     * Create a paginator for iterating through the list opportunities endpoint.
     *
     * Automatically fetches subsequent pages using offset-based pagination.
     *
     * @param  OpportunityQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?OpportunityQuery $query = null): Paginator
    {
        $query = $query ?? OpportunityQuery::make();

        return new Paginator(
            fn (OpportunityQuery $q) => $this->list($q),
            $query
        );
    }

    /**
     * Get a specific opportunity by ID
     *
     * @param  int  $opportunityId  The opportunity ID
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $opportunityId): array
    {
        return $this->connector->send(new GetOpportunity($opportunityId))->json();
    }

    /**
     * Create a new opportunity
     *
     * @param  array<string, mixed>  $data  Opportunity data
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        return $this->connector->send(new CreateOpportunity($data))->json();
    }

    /**
     * Update an existing opportunity
     *
     * @param  int  $opportunityId  The opportunity ID to update
     * @param  array<string, mixed>  $data  Opportunity data to update
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(int $opportunityId, array $data): array
    {
        return $this->connector->send(new UpdateOpportunity($opportunityId, $data))->json();
    }

    /**
     * Get opportunity model
     *
     * Retrieves the custom fields for the Opportunity object.
     *
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getModel(): array
    {
        return $this->connector->send(new GetOpportunityModel)->json();
    }
}
