<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\LeadSources\CreateLeadSource;
use Toddstoker\KeapSdk\Requests\V2\LeadSources\DeleteLeadSource;
use Toddstoker\KeapSdk\Requests\V2\LeadSources\GetLeadSource;
use Toddstoker\KeapSdk\Requests\V2\LeadSources\ListLeadSources;
use Toddstoker\KeapSdk\Requests\V2\LeadSources\UpdateLeadSource;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\LeadSourceQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Lead Sources Resource (v2)
 *
 * Provides methods for managing lead sources in Keap. Lead sources track
 * where your leads come from (e.g., Google Ads, Facebook, referrals, etc.)
 * and help measure marketing ROI.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class LeadSourcesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List lead sources with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  LeadSourceQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     lead_sources: array<int, array{
     *         id: string,
     *         name: string,
     *         description?: string,
     *         lead_source_category_id?: string,
     *         vendor?: string,
     *         medium?: string,
     *         message?: string,
     *         start_time?: string,
     *         end_time?: string,
     *         status?: string,
     *         create_time?: string,
     *         update_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?LeadSourceQuery $query = null): array
    {
        $query = $query ?? LeadSourceQuery::make();

        $response = $this->connector->send(new ListLeadSources($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list lead sources endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  LeadSourceQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?LeadSourceQuery $query = null): Paginator
    {
        $query = $query ?? LeadSourceQuery::make();

        return new Paginator(
            fn (LeadSourceQuery $q) => $this->list($q),
            $query,
            'lead_sources'
        );
    }

    /**
     * Get a lead source
     *
     * Retrieves a single lead source by ID.
     *
     * @param  string  $leadSourceId  The lead source ID
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     lead_source_category_id?: string,
     *     vendor?: string,
     *     medium?: string,
     *     message?: string,
     *     start_time?: string,
     *     end_time?: string,
     *     status?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(string $leadSourceId): array
    {
        $response = $this->connector->send(new GetLeadSource($leadSourceId));

        return $response->json();
    }

    /**
     * Create a lead source
     *
     * Creates a new lead source.
     *
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
     * }  $data  Lead source data
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     lead_source_category_id?: string,
     *     vendor?: string,
     *     medium?: string,
     *     message?: string,
     *     start_time?: string,
     *     end_time?: string,
     *     status?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        $response = $this->connector->send(new CreateLeadSource($data));

        return $response->json();
    }

    /**
     * Update a lead source
     *
     * Updates an existing lead source. Only provided fields will be updated.
     *
     * @param  string  $leadSourceId  The lead source ID
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
     * }  $data  Lead source data to update
     * @return array{
     *     id: string,
     *     name: string,
     *     description?: string,
     *     lead_source_category_id?: string,
     *     vendor?: string,
     *     medium?: string,
     *     message?: string,
     *     start_time?: string,
     *     end_time?: string,
     *     status?: string,
     *     create_time?: string,
     *     update_time?: string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(string $leadSourceId, array $data): array
    {
        $response = $this->connector->send(new UpdateLeadSource($leadSourceId, $data));

        return $response->json();
    }

    /**
     * Delete a lead source
     *
     * Deletes a lead source permanently.
     *
     * @param  string  $leadSourceId  The lead source ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(string $leadSourceId): void
    {
        $this->connector->send(new DeleteLeadSource($leadSourceId));
    }
}
